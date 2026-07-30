# DigitSpots HMS Solution - Deployment & Infrastructure

This document outlines how the DigitSpots HMS application is currently deployed, its infrastructure architecture on AWS, and the roadmap for modernizing the deployment lifecycle.

---

## 🌍 Current AWS Infrastructure Architecture

The application has been migrated from a local, on-premise LAN setup to Amazon Web Services (AWS) using a **Single EC2 + RDS + ALB** approach.

### Components:
- **Application Load Balancer (ALB)**: Native HTTPS offloading using an existing AWS Certificate Manager (ACM) certificate for `*.hmssolution.org`. Redirects port 80 traffic to 443.
- **Compute (Amazon EC2)**: A single Ubuntu 22.04 EC2 instance running the application inside a Docker container (`hms-web`). The application directory is mapped to the container as a volume.
- **Database (Amazon RDS)**: A managed MariaDB 10.5 instance, fully decoupled from the application server for improved data resilience.
- **Security Groups**: Tightened security allowing HTTP/HTTPS to the ALB, ALB to EC2 over port 80, and EC2 to RDS over port 3306.

---

## 🚀 Current Deployment Process (Manual)

We currently **do not use a CI/CD pipeline**. The deployment process is entirely manual and relies on imperative bash scripts.

### Steps to Deploy:
1. **Infrastructure Provisioning**: Infrastructure is created imperatively by running AWS CLI commands sequentially (documented in `AWS_CLI_COMMANDS.md` and `deploy_imperative.sh`).
2. **Code Synchronization (`sync_code.sh`)**:
   - The developer runs the sync script locally, providing the new EC2 Public IP and RDS Endpoint.
   - The script uses `sed` to dynamically find and replace hardcoded database credentials and local domain strings in the PHP codebase.
   - `rsync` transfers the code from the developer's local machine to the EC2 instance via SSH.
   - The script connects to the EC2 instance via SSH to build a new Docker image and restart the container.
   - Finally, the local code files are reverted back to their local Docker Compose defaults.

---

## ⚠️ Current Shortcomings & Required Improvements

Our deployment and infrastructure architecture currently face several bottlenecks that prevent true cloud scalability and reliability.

### 1. Lack of Automated CI/CD Pipeline
- **Current State**: Deployments are executed manually from a local machine using `sync_code.sh`. This is error-prone, untraceable, and creates a bottleneck around the developer's machine.
- **Improvement**: Implement a CI/CD pipeline using **GitHub Actions**. Pushes to the main branch should automatically lint the code, build the Docker image, push it to Amazon ECR, and trigger a rolling update to the deployment environment.

### 2. Inability to use Auto Scaling Groups (ASG)
- **Current State**: The application runs on a single EC2 instance. If the instance fails or traffic spikes, the application will go down. We cannot use an Auto Scaling Group right now because **the application stores uploaded files on the local EC2 instance storage**.
- **Improvement**: Once the application code is updated to store uploads in an **Amazon S3 Bucket**, we can configure an **Auto Scaling Group**. The ASG will span multiple Availability Zones and automatically spin up or shut down EC2 instances behind the ALB based on CPU/Memory traffic load.

### 3. Imperative Infrastructure Management
- **Current State**: Infrastructure is provisioned using sequential AWS CLI bash commands (`deploy_imperative.sh`). This makes it difficult to track state, manage updates, or replicate environments (like staging vs. production).
- **Improvement**: Migrate to **Infrastructure as Code (IaC)** using **Terraform** or AWS CloudFormation. This will allow us to version-control the infrastructure, automatically provision entire environments in minutes, and manage changes predictably.

### 4. Configuration Management
- **Current State**: Injecting secrets requires modifying source files directly before upload.
- **Improvement**: Store infrastructure secrets (like database passwords) in **AWS Secrets Manager** or Systems Manager Parameter Store, and securely inject them into the application environment at runtime.
