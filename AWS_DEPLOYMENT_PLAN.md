# AWS Deployment Plan

This document outlines the step-by-step strategy for migrating the HMS solution to AWS. It is structured into two phases: our immediate **Phase 1 (Single EC2 + New ALB)** deployment, and a roadmap for **Phase 2 (Auto Scaling & CI/CD)**.

---

## Phase 1: Current Deployment (EC2 + RDS + New ALB)

For the immediate launch, we will separate the database from the compute layer and use an Application Load Balancer to leverage the existing AWS Certificate Manager (ACM) SSL certificate. We will completely bypass the old Load Balancer currently linked to the domain.

### Architecture Overview
- **Database Layer:** Amazon RDS (MariaDB/MySQL) in `us-east-1`.
- **Application Layer:** A single Amazon EC2 instance running Docker (Apache + PHP).
- **Domain & Load Balancing:** A **brand new** Application Load Balancer (ALB) attached to the existing `*.hmssolution.org` ACM Certificate, with Route 53 records updated to point to the new ALB.

### Deployment Steps
1. **Provision the Database (Amazon RDS)**
   - Launch an RDS MariaDB or MySQL instance in `us-east-1`.
   - Connect locally and import the `hmsdb.sql` database dump.

2. **Provision the Application Server (Amazon EC2)**
   - Launch an EC2 instance in `us-east-1`.
   - Attach a Security Group allowing HTTP (Port 80) from the Load Balancer, and SSH (Port 22) from your IP.
   - Run the Docker container:
     ```bash
     docker build -t hms-web .
     docker run -d -p 80:80 --restart always --name hms-app hms-web
     ```

3. **Provision a NEW Load Balancer**
   - Create a **new** Application Load Balancer (ALB) to avoid interfering with old deployments.
   - Create a Target Group pointing to the new EC2 instance on Port 80.
   - Attach the existing ACM Certificate (`arn:aws:acm:us-east-1:905418229426:certificate/a3e1843b...`) for `*.hmssolution.org` to the new ALB to offload HTTPS natively.

4. **Cutover Route 53 DNS**
   - We will update the Route 53 A-Alias records for `hmssolution.org` (and subdomains like `admin`, `hotel`, `pos`, `travel`) to stop pointing to the old ALB (`hms-alb-2013794502...`) and point them to the newly created ALB. Traffic will immediately begin routing to the new infrastructure securely!

---

## Phase 2: Future Architecture (Auto Scaling + GitHub Actions)

Once the Phase 1 deployment is stabilized and traffic grows, we easily transition to a highly available setup since the Load Balancer is already in place.

### Future Architecture Overview
- **Compute:** Auto Scaling Group (ASG) managing multiple EC2 instances behind the ALB.
- **Sticky Sessions:** Enabled on the ALB Target Group to preserve PHP login sessions across instances.
- **Shared Storage:** Amazon EFS (Elastic File System) mounted across all ASG instances so uploaded files are instantly shared.
- **CI/CD:** GitHub Actions pipeline pushing images to Amazon ECR and triggering rolling updates to the ASG.
