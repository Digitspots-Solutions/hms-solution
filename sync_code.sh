#!/bin/bash
# Sync code to EC2 and deploy Docker container

# Make sure you provide the EC2 Public IP and the RDS endpoint after CloudFormation finishes
EC2_PUBLIC_IP=$1
RDS_ENDPOINT=$2
RDS_PASSWORD=${3:-$RDS_PASSWORD}
KEY_PATH="~/.ssh/hms-new-key.pem"

if [ -z "$EC2_PUBLIC_IP" ] || [ -z "$RDS_ENDPOINT" ] || [ -z "$RDS_PASSWORD" ]; then
  echo "Usage: ./sync_code.sh <EC2_PUBLIC_IP> <RDS_ENDPOINT> <RDS_PASSWORD>"
  echo "Or set RDS_PASSWORD env variable and run: ./sync_code.sh <EC2_PUBLIC_IP> <RDS_ENDPOINT>"
  exit 1
fi

echo "Replacing connection strings dynamically for deployment..."
find . -name "connection_string.php" -type f -exec sed -i "s/define(\"DB_SERVER\", \"db\");/define(\"DB_SERVER\", \"$RDS_ENDPOINT\");/g" {} +
find . -name "connection_string.php" -type f -exec sed -i 's/define("DB_USER", "root");/define("DB_USER", "hmsadmin");/g' {} +
find . -name "connection_string.php" -type f -exec sed -i "s/define(\"DB_PASS\", \"\");/define(\"DB_PASS\", \"$RDS_PASSWORD\");/g" {} +

echo "Replacing domain URLs for production..."
find . -name "php_paths.php" -type f -exec sed -i 's|http://127.0.0.1/|https://hmssolution.org/|g' {} +
find . -name "jspath.js" -type f -exec sed -i 's|http://127.0.0.1/|https://hmssolution.org/|g' {} +

echo "Syncing files to EC2 instance..."
rsync -avz --exclude '.git' -e "ssh -i $KEY_PATH -o StrictHostKeyChecking=no" ./ ubuntu@$EC2_PUBLIC_IP:/home/ubuntu/hms-solution/

echo "Starting deployment on EC2..."
ssh -i $KEY_PATH -o StrictHostKeyChecking=no ubuntu@$EC2_PUBLIC_IP << EOF
  cd /home/ubuntu/hms-solution
  sudo docker build -t hms-web .
  sudo docker stop hms-app || true
  sudo docker rm hms-app || true
  sudo docker run -d -p 80:80 --restart always --name hms-app -v /home/ubuntu/hms-solution:/var/www/html hms-web
EOF

echo "Done! The application is live."

# Revert local files back to Docker compose defaults
find . -name "connection_string.php" -type f -exec sed -i "s/define(\"DB_SERVER\", \"$RDS_ENDPOINT\");/define(\"DB_SERVER\", \"db\");/g" {} +
find . -name "connection_string.php" -type f -exec sed -i 's/define("DB_USER", "hmsadmin");/define("DB_USER", "root");/g' {} +
find . -name "connection_string.php" -type f -exec sed -i "s/define(\"DB_PASS\", \"$RDS_PASSWORD\");/define(\"DB_PASS\", \"\");/g" {} +
find . -name "php_paths.php" -type f -exec sed -i 's|https://hmssolution.org/|http://127.0.0.1/|g' {} +
find . -name "jspath.js" -type f -exec sed -i 's|https://hmssolution.org/|http://127.0.0.1/|g' {} +
echo "Reverted local files to Docker compose defaults."
