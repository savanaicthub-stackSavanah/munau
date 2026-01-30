# Munau College Portal - AWS Deployment Guide

## Prerequisites

- AWS Account with appropriate permissions
- Domain name (or use Route53)
- SSL Certificate (AWS Certificate Manager recommended)
- Database backups configured
- Email service configured (SES or third-party SMTP)
- Payment gateway accounts (Paystack, Flutterwave)

## AWS Infrastructure Architecture

### Compute
- **EC2 Instances**: 2-3 instances for high availability (t3.medium or larger)
- **Auto Scaling Group**: Scale based on CPU/memory metrics
- **Application Load Balancer (ALB)**: Distribute traffic across instances

### Database
- **RDS MySQL 8.0**: Multi-AZ deployment for high availability
- **Backup**: Automated daily backups, 30-day retention
- **Read Replica**: Optional for reporting/analytics

### Storage
- **S3 Buckets**: 
  - Application documents (encrypted)
  - Student photos and ID cards
  - Admission letters and transcripts
  - Backup storage
- **CloudFront**: CDN for static assets

### Caching & Queues
- **ElastiCache (Redis)**: Session management, caching
- **SQS**: Email queue, payment processing queue

### Security
- **VPC**: Custom VPC with public/private subnets
- **Security Groups**: Restrictive ingress/egress rules
- **WAF**: AWS Web Application Firewall
- **Secrets Manager**: Store sensitive credentials
- **KMS**: Encryption for data at rest

### Monitoring & Logging
- **CloudWatch**: Application logs, metrics
- **CloudTrail**: Audit logs for compliance
- **SNS**: Notifications for alerts

## Step-by-Step Deployment

### 1. Prepare AWS Environment

```bash
# Create VPC and subnets
aws ec2 create-vpc --cidr-block 10.0.0.0/16

# Create security groups
aws ec2 create-security-group \
  --group-name munau-college-sg \
  --description "Security group for Munau College Portal"

# Create S3 buckets
aws s3 mb s3://munau-college-documents --region us-east-1
aws s3 mb s3://munau-college-backups --region us-east-1
```

### 2. Set Up RDS Database

```bash
# Create RDS instance
aws rds create-db-instance \
  --db-instance-identifier munau-college-db \
  --db-instance-class db.t3.medium \
  --engine mysql \
  --master-username admin \
  --master-user-password 'YourStrongPassword' \
  --allocated-storage 100 \
  --storage-type gp2 \
  --multi-az \
  --backup-retention-period 30
```

### 3. Set Up ElastiCache Redis

```bash
# Create Redis cluster
aws elasticache create-cache-cluster \
  --cache-cluster-id munau-redis \
  --cache-node-type cache.t3.micro \
  --engine redis \
  --engine-version 7.0
```

### 4. Prepare EC2 Instance

```bash
# Connect to EC2 instance
ssh -i your-key.pem ec2-user@your-instance-ip

# Update system
sudo yum update -y
sudo yum install -y php php-mysql php-gd php-xml composer

# Install Nginx
sudo yum install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Create application directory
sudo mkdir -p /var/www/html
sudo chown -R $USER:$USER /var/www/html

# Clone repository
cd /var/www/html
git clone https://your-repo-url.git munau-college
cd munau-college

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
sudo chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
```

### 5. Configure Environment

```bash
# Copy and configure .env
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database (update .env with RDS endpoint)
# IMPORTANT: Use Secrets Manager for credentials
aws secretsmanager create-secret \
  --name munau-college/db-credentials \
  --secret-string '{"username":"admin","password":"YourStrongPassword"}'
```

### 6. Run Database Migrations

```bash
# Run migrations
php artisan migrate:fresh --seed

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Configure Nginx

Create `/etc/nginx/sites-available/munau-college`:

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    root /var/www/html/public;
    index index.php;

    # Gzip compression
    gzip on;
    gzip_types text/css text/javascript image/svg+xml;
    gzip_min_length 1024;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=general:10m rate=10r/s;
    limit_req zone=general burst=20 nodelay;
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/munau-college /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 8. Set Up SSL/TLS

```bash
# Using AWS Certificate Manager
aws acm request-certificate \
  --domain-name your-domain.com \
  --validation-method DNS
```

### 9. Configure Load Balancer

```bash
# Create ALB
aws elbv2 create-load-balancer \
  --name munau-college-alb \
  --subnets subnet-xxx subnet-yyy \
  --security-groups sg-xxx

# Create target group
aws elbv2 create-target-group \
  --name munau-college-tg \
  --protocol HTTP \
  --port 80 \
  --vpc-id vpc-xxx

# Register targets (EC2 instances)
aws elbv2 register-targets \
  --target-group-arn arn:aws:elasticloadbalancing:... \
  --targets Id=i-xxx Id=i-yyy
```

### 10. Set Up Auto Scaling

```bash
# Create launch template
aws ec2 create-launch-template \
  --launch-template-name munau-college-lt \
  --version-description "Munau College Portal" \
  --launch-template-data file://launch-template.json

# Create auto scaling group
aws autoscaling create-auto-scaling-group \
  --auto-scaling-group-name munau-college-asg \
  --launch-template LaunchTemplateName=munau-college-lt \
  --min-size 2 \
  --max-size 5 \
  --desired-capacity 3 \
  --vpc-zone-identifier "subnet-xxx,subnet-yyy"

# Create scaling policies
aws autoscaling put-scaling-policy \
  --auto-scaling-group-name munau-college-asg \
  --policy-name scale-up \
  --policy-type TargetTrackingScaling \
  --target-tracking-configuration file://target-tracking.json
```

### 11. Configure CloudFront

```bash
# Create CloudFront distribution
aws cloudfront create-distribution \
  --origin-domain-name your-alb-domain \
  --default-root-object index.php
```

### 12. Set Up Monitoring & Alerts

```bash
# Create SNS topic for alerts
aws sns create-topic --name munau-college-alerts

# Create CloudWatch alarms
aws cloudwatch put-metric-alarm \
  --alarm-name high-cpu \
  --alarm-description "Alert when CPU is high" \
  --metric-name CPUUtilization \
  --namespace AWS/EC2 \
  --statistic Average \
  --period 300 \
  --threshold 80 \
  --comparison-operator GreaterThanThreshold \
  --alarm-actions arn:aws:sns:...
```

### 13. Backup Strategy

```bash
# Enable automated RDS backups (already configured)
# Create S3 versioning
aws s3api put-bucket-versioning \
  --bucket munau-college-backups \
  --versioning-configuration Status=Enabled

# Create backup Lambda function
# Schedule to run daily using EventBridge/CloudWatch Events
```

### 14. Security Hardening

- Enable VPC Flow Logs
- Configure AWS WAF rules
- Enable CloudTrail for audit logging
- Use AWS Secrets Manager for all credentials
- Implement MFA for all admin accounts
- Regular security group audits
- Enable S3 bucket encryption
- Configure bucket policies to prevent public access

## Post-Deployment Verification

```bash
# Test application health
curl -I https://your-domain.com

# Check database connectivity
php artisan tinker
# Run: DB::connection()->getPdo();

# Verify cache is working
php artisan cache:clear
php artisan cache:verify

# Check file permissions
ls -la /var/www/html/storage
ls -la /var/www/html/bootstrap/cache

# Monitor logs
tail -f /var/log/nginx/error.log
tail -f /var/www/html/storage/logs/laravel.log
```

## Maintenance & Updates

```bash
# Regular updates
sudo yum update -y
composer update --no-dev

# Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Database backups
php artisan backup:run

# Log rotation
sudo logrotate -f /etc/logrotate.d/nginx
```

## Disaster Recovery

1. **RDS Automated Backups**: Retain for 30 days
2. **S3 Cross-Region Replication**: For critical documents
3. **Database Snapshots**: Weekly manual snapshots
4. **Application Code**: Git repository with remote backup
5. **Configuration Backup**: Store .env files in AWS Secrets Manager

## Cost Optimization

- Use Reserved Instances for predictable workloads
- Implement S3 lifecycle policies for old backups
- Use CloudFront for static content delivery
- Monitor AWS Trusted Advisor recommendations
- Right-size RDS instance based on usage
- Use spot instances for non-critical workloads

## Support & Maintenance

- Monitor AWS Health Dashboard
- Set up SNS notifications for critical events
- Weekly security audits
- Monthly backup restoration tests
- Quarterly disaster recovery drills
