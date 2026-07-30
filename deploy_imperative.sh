#!/bin/bash
set -e

echo "Creating ALB Security Group..."
ALB_SG_ID=$(aws ec2 create-security-group --group-name hms-alb-sg-$(date +%s) --description "ALB SG" --vpc-id vpc-0532050d457667e55 --profile hms --region us-east-1 --query 'GroupId' --output text)
aws ec2 authorize-security-group-ingress --group-id $ALB_SG_ID --protocol tcp --port 80 --cidr 0.0.0.0/0 --profile hms --region us-east-1
aws ec2 authorize-security-group-ingress --group-id $ALB_SG_ID --protocol tcp --port 443 --cidr 0.0.0.0/0 --profile hms --region us-east-1

echo "Creating Web EC2 Security Group..."
WEB_SG_ID=$(aws ec2 create-security-group --group-name hms-web-sg-$(date +%s) --description "EC2 SG" --vpc-id vpc-0532050d457667e55 --profile hms --region us-east-1 --query 'GroupId' --output text)
aws ec2 authorize-security-group-ingress --group-id $WEB_SG_ID --protocol tcp --port 22 --cidr 0.0.0.0/0 --profile hms --region us-east-1
aws ec2 authorize-security-group-ingress --group-id $WEB_SG_ID --protocol tcp --port 80 --source-group $ALB_SG_ID --profile hms --region us-east-1

echo "Creating RDS Security Group..."
RDS_SG_ID=$(aws ec2 create-security-group --group-name hms-rds-sg-$(date +%s) --description "RDS SG" --vpc-id vpc-0532050d457667e55 --profile hms --region us-east-1 --query 'GroupId' --output text)
aws ec2 authorize-security-group-ingress --group-id $RDS_SG_ID --protocol tcp --port 3306 --source-group $WEB_SG_ID --profile hms --region us-east-1
aws ec2 authorize-security-group-ingress --group-id $RDS_SG_ID --protocol tcp --port 3306 --cidr 0.0.0.0/0 --profile hms --region us-east-1

echo "Launching RDS MariaDB Instance (this takes 5-10 minutes)..."
aws rds create-db-instance \
  --db-instance-identifier hms-db-prod-$(date +%s) \
  --allocated-storage 20 \
  --db-instance-class db.t3.micro \
  --engine mariadb \
  --engine-version 10.5 \
  --master-username hmsadmin \
  --master-user-password "<YOUR_RDS_PASSWORD>" \
  --vpc-security-group-ids $RDS_SG_ID \
  --db-name hmsdb \
  --publicly-accessible \
  --profile hms --region us-east-1

echo "Retrieving Latest Ubuntu AMI..."
AMI_ID=$(aws ssm get-parameters --names /aws/service/canonical/ubuntu/server/22.04/stable/current/amd64/hvm/ebs-gp2/ami-id --query "Parameters[0].Value" --output text --profile hms --region us-east-1)

echo "Launching EC2 Instance..."
INSTANCE_ID=$(aws ec2 run-instances \
  --image-id $AMI_ID \
  --count 1 \
  --instance-type t3.small \
  --key-name hms-server-key \
  --security-group-ids $WEB_SG_ID \
  --subnet-id subnet-0e92557cbb0a2b59b \
  --tag-specifications 'ResourceType=instance,Tags=[{Key=Name,Value=hms-web-server}]' \
  --query "Instances[0].InstanceId" --output text \
  --profile hms --region us-east-1)

echo "Creating Application Load Balancer..."
ALB_ARN=$(aws elbv2 create-load-balancer \
  --name hms-new-alb-$(date +%s) \
  --subnets subnet-0e92557cbb0a2b59b subnet-0f6b609441223d71e subnet-0664b286668fb9534 subnet-03f165f8a5fe062fe subnet-07fb064b5d93424ef subnet-024d1b196a7508b24 \
  --security-groups $ALB_SG_ID \
  --query "LoadBalancers[0].LoadBalancerArn" --output text \
  --profile hms --region us-east-1)

echo "Creating Target Group..."
TG_ARN=$(aws elbv2 create-target-group \
  --name hms-tg-$(date +%s) \
  --protocol HTTP \
  --port 80 \
  --vpc-id vpc-0532050d457667e55 \
  --health-check-path /digit-spot-hms/index.html \
  --query "TargetGroups[0].TargetGroupArn" --output text \
  --profile hms --region us-east-1)

echo "Registering EC2 with Target Group..."
aws elbv2 register-targets \
  --target-group-arn $TG_ARN \
  --targets Id=$INSTANCE_ID \
  --profile hms --region us-east-1

echo "Creating HTTPS Listener..."
aws elbv2 create-listener \
  --load-balancer-arn $ALB_ARN \
  --protocol HTTPS \
  --port 443 \
  --certificates CertificateArn=arn:aws:acm:us-east-1:905418229426:certificate/a3e1843b-7dbb-4434-81ac-cc640f7bb4f4 \
  --default-actions Type=forward,TargetGroupArn=$TG_ARN \
  --profile hms --region us-east-1 > /dev/null

echo "Creating HTTP Listener (Redirect)..."
aws elbv2 create-listener \
  --load-balancer-arn $ALB_ARN \
  --protocol HTTP \
  --port 80 \
  --default-actions Type=redirect,RedirectConfig="{Protocol=HTTPS,Port=443,StatusCode=HTTP_301}" \
  --profile hms --region us-east-1 > /dev/null

echo "Infrastructure provisioned!"
echo "ALB ARN: $ALB_ARN"
echo "EC2 ID: $INSTANCE_ID"
echo "RDS ID: hms-db-prod"
