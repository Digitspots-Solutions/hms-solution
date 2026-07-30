# AWS CLI Infrastructure Commands

This document contains the exact AWS CLI commands used to provision the infrastructure for the HMS solution. This acts as an imperative blueprint that can be easily translated into Terraform code in the future.

## Prerequisites
- AWS Profile: `hms`
- Region: `us-east-1`
- VPC ID: `vpc-0532050d457667e55`
- ACM Certificate ARN: `arn:aws:acm:us-east-1:905418229426:certificate/a3e1843b-7dbb-4434-81ac-cc640f7bb4f4`
- Route 53 Hosted Zone ID: `Z038549833HY1UUK8AYHQ`

## 1. Security Groups

**Create ALB Security Group:**
```bash
aws ec2 create-security-group --group-name hms-alb-sg --description "Allow HTTP/HTTPS to ALB" --vpc-id vpc-0532050d457667e55 --profile hms
# Capture the Group ID into ALB_SG_ID
aws ec2 authorize-security-group-ingress --group-id $ALB_SG_ID --protocol tcp --port 80 --cidr 0.0.0.0/0 --profile hms
aws ec2 authorize-security-group-ingress --group-id $ALB_SG_ID --protocol tcp --port 443 --cidr 0.0.0.0/0 --profile hms
```

**Create EC2 Web Security Group:**
```bash
aws ec2 create-security-group --group-name hms-web-sg --description "Allow traffic from ALB and SSH" --vpc-id vpc-0532050d457667e55 --profile hms
# Capture the Group ID into WEB_SG_ID
aws ec2 authorize-security-group-ingress --group-id $WEB_SG_ID --protocol tcp --port 22 --cidr 0.0.0.0/0 --profile hms
aws ec2 authorize-security-group-ingress --group-id $WEB_SG_ID --protocol tcp --port 80 --source-group $ALB_SG_ID --profile hms
```

**Create RDS Security Group:**
```bash
aws ec2 create-security-group --group-name hms-rds-sg --description "Allow MySQL traffic from EC2" --vpc-id vpc-0532050d457667e55 --profile hms
# Capture the Group ID into RDS_SG_ID
aws ec2 authorize-security-group-ingress --group-id $RDS_SG_ID --protocol tcp --port 3306 --source-group $WEB_SG_ID --profile hms
# Temporary access for manual local data import
aws ec2 authorize-security-group-ingress --group-id $RDS_SG_ID --protocol tcp --port 3306 --cidr 0.0.0.0/0 --profile hms
```

## 2. RDS Database Instance

**Launch the MariaDB RDS Instance:**
```bash
aws rds create-db-instance \
  --db-instance-identifier hms-db-prod \
  --allocated-storage 20 \
  --db-instance-class db.t3.micro \
  --engine mariadb \
  --engine-version 10.5 \
  --master-username hmsadmin \
  --master-user-password "<YOUR_RDS_PASSWORD>" \
  --vpc-security-group-ids $RDS_SG_ID \
  --db-name hmsdb \
  --publicly-accessible \
  --profile hms
```

## 3. EC2 Application Server

**Retrieve Latest Ubuntu 22.04 AMI:**
```bash
AMI_ID=$(aws ssm get-parameters --names /aws/service/canonical/ubuntu/server/22.04/stable/current/amd64/hvm/ebs-gp2/ami-id --query "Parameters[0].Value" --output text --profile hms)
```

**Launch EC2 Instance:**
```bash
aws ec2 run-instances \
  --image-id $AMI_ID \
  --count 1 \
  --instance-type t3.small \
  --key-name hms-server-key \
  --security-group-ids $WEB_SG_ID \
  --subnet-id subnet-0e92557cbb0a2b59b \
  --tag-specifications 'ResourceType=instance,Tags=[{Key=Name,Value=hms-web-server}]' \
  --profile hms
# Capture the Instance ID into INSTANCE_ID
```

## 4. Load Balancer (ALB) & Listeners

**Create the ALB:**
```bash
aws elbv2 create-load-balancer \
  --name hms-new-alb \
  --subnets subnet-0e92557cbb0a2b59b subnet-0f6b609441223d71e subnet-0664b286668fb9534 subnet-03f165f8a5fe062fe subnet-07fb064b5d93424ef subnet-024d1b196a7508b24 \
  --security-groups $ALB_SG_ID \
  --profile hms
# Capture the ALB ARN and DNS Name
```

**Create the Target Group:**
```bash
aws elbv2 create-target-group \
  --name hms-tg \
  --protocol HTTP \
  --port 80 \
  --vpc-id vpc-0532050d457667e55 \
  --health-check-path /digit-spot-hms/index.html \
  --profile hms
# Capture the Target Group ARN into TG_ARN
```

**Register the EC2 Instance to the Target Group:**
```bash
aws elbv2 register-targets \
  --target-group-arn $TG_ARN \
  --targets Id=$INSTANCE_ID \
  --profile hms
```

**Create HTTPS Listener (Uses ACM Certificate):**
```bash
aws elbv2 create-listener \
  --load-balancer-arn $ALB_ARN \
  --protocol HTTPS \
  --port 443 \
  --certificates CertificateArn=arn:aws:acm:us-east-1:905418229426:certificate/a3e1843b-7dbb-4434-81ac-cc640f7bb4f4 \
  --default-actions Type=forward,TargetGroupArn=$TG_ARN \
  --profile hms
```

**Create HTTP Listener (Redirects to HTTPS):**
```bash
aws elbv2 create-listener \
  --load-balancer-arn $ALB_ARN \
  --protocol HTTP \
  --port 80 \
  --default-actions Type=redirect,RedirectConfig="{Protocol=HTTPS,Port=443,StatusCode=HTTP_301}" \
  --profile hms
```

## 5. Route 53 DNS Cutover

**Create the Change Batch File (`change-batch.json`):**
```json
{
  "Comment": "Update A Alias to point to new ALB",
  "Changes": [
    {
      "Action": "UPSERT",
      "ResourceRecordSet": {
        "Name": "hmssolution.org.",
        "Type": "A",
        "AliasTarget": {
          "HostedZoneId": "<ALB_HOSTED_ZONE_ID>",
          "DNSName": "dualstack.<ALB_DNS_NAME>",
          "EvaluateTargetHealth": false
        }
      }
    }
  ]
}
```

**Execute the Route 53 Cutover:**
```bash
aws route53 change-resource-record-sets \
  --hosted-zone-id Z038549833HY1UUK8AYHQ \
  --change-batch file://change-batch.json \
  --profile hms
```
