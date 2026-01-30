# Munau College Management System - Project Setup Guide

## Overview
A comprehensive, secure, and fully responsive digital platform for Munau College of Health Sciences and Technology, combining a public website, student portal, admission management system, and integrated finance module.

## Technology Stack
- **Backend**: Laravel 11.x (PHP)
- **Frontend**: Bootstrap 5, HTML5, JavaScript
- **Database**: MySQL
- **Authentication**: Laravel Sanctum / Session-based
- **Security**: Encryption, Password Hashing (bcrypt), Audit Trails, Role-Based Access Control
- **Deployment**: AWS (EC2, RDS, S3)

## Project Structure

```
├── app/
│   ├── Models/                 # Database Models
│   │   ├── User.php
│   │   ├── Student.php
│   │   ├── Program.php
│   │   ├── Department.php
│   │   ├── Admission.php
│   │   ├── SchoolFee.php
│   │   ├── AcademicSession.php
│   │   └── ... other models
│   ├── Http/
│   │   ├── Controllers/        # Request Controllers
│   │   │   ├── WebController.php        # Public Website
│   │   │   ├── AuthController.php       # Authentication
│   │   │   ├── StudentPortalController.php
│   │   │   ├── AdmissionController.php
│   │   │   └── Admin/          # Admin Controllers
│   │   └── Middleware/         # Authentication & Authorization Middleware
│   └── Services/               # Business Logic Services
│       ├── AuthenticationService.php
│       ├── FinanceService.php
│       ├── AdmissionService.php
│       └── NotificationService.php
├── database/
│   └── migrations/             # Database Migrations
├── routes/
│   ├── web.php                # Public & Portal Routes
│   └── api.php                # API Routes (if needed)
├── resources/
│   ├── views/
│   │   ├── layouts/           # Layout Templates
│   │   │   ├── web.blade.php          # Public Website Layout
│   │   │   └── student-portal.blade.php # Portal Layout
│   │   ├── web/               # Public Website Views
│   │   │   ├── home.blade.php
│   │   │   ├── about.blade.php
│   │   │   ├── programs.blade.php
│   │   │   ├── contact.blade.php
│   │   │   └── ... other pages
│   │   └── student/           # Student Portal Views
│   │       ├── dashboard.blade.php
│   │       ├── profile.blade.php
│   │       ├── courses.blade.php
│   │       └── ... other portal pages
│   └── css/                   # Custom Stylesheets
└── config/
    ├── app.php
    ├── database.php
    ├── mail.php
    └── college.php            # Custom Configuration
```

## Installation & Setup

### 1. Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM (optional, for asset compilation)
- Apache/Nginx Web Server

### 2. Environment Setup

```bash
# Clone the repository
git clone <repository-url>
cd munau-college-system

# Install PHP dependencies
composer install

# Copy environment file
cp laravel.env .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_DATABASE=munau_college
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed initial data (optional)
php artisan db:seed

# Create admin user
php artisan tinker
>>> App\Models\User::create([
    'role' => 'admin',
    'email' => 'admin@munaucollege.edu.ng',
    'password' => bcrypt('secure_password'),
    'first_name' => 'Admin',
    'last_name' => 'User',
    'is_active' => true,
    'is_verified' => true,
])
```

### 4. Start Development Server

```bash
# Using Laravel's built-in server
php artisan serve

# Server will be available at http://localhost:8000
```

## Key Features Implemented

### Public Website
- **Home Page**: Hero section, featured programs, latest news, events
- **About Page**: Vision, mission, core values, management profiles
- **Programs Catalog**: Browse all academic programs
- **Departments**: Department information and course listings
- **Admission Requirements**: Program-specific admission criteria
- **News & Events**: Latest college updates and events
- **Gallery**: Photo albums and event galleries
- **Management & Governing Council**: Staff and leadership profiles
- **Downloads**: Public resources and documents
- **Contact Form**: Visitor inquiry system with Google Maps
- **Search Functionality**: Full-text search across content

### Student Portal (Secure)
- **User Authentication**: Email/password with optional 2FA
- **Profile Management**: Biodata, contact information, emergency contacts
- **Course Registration**: Register/drop courses per semester
- **Academic Records**:
  - Lecture Timetable
  - Examination Schedule
  - Results and Grades
  - Transcript Download
- **Finance Module**:
  - View fee structure
  - Payment status and history
  - Online fee payment
  - Receipt generation and download
  - Payment plans
- **Hostel Management**:
  - Hostel application
  - Room allocation viewing
  - Check-in/check-out
  - Complaint submission
- **ID Card Request**: Request and track student ID card
- **Notifications**: Real-time notifications for academic and finance updates
- **Dashboard**: Overview of important information and deadlines

### Admission Management System
- **Online Application**: Multi-step admission form
- **Document Upload**: Upload and manage supporting documents
- **Document Verification**: Admin verification workflow
- **Application Tracking**: Applicants can track application status
- **Screening & Shortlisting**: Automated or manual candidate selection
- **Interview Management**: Schedule and manage interviews
- **Admission Decision**: Generate and send admission letters
- **Acceptance Fee Payment**: Online payment tracking
- **Automatic Student Onboarding**: Create student accounts upon acceptance

### Finance Module
- **Fee Structure Management**: Define fees per program/level/session
- **School Fee Generation**: Automatic fee calculation and assignment
- **Payment Processing**: Support for multiple payment methods
  - Bank transfer
  - Card payments
  - Paystack integration
  - Stripe integration
- **Payment Receipts**: Auto-generated receipts with PDF download
- **Payment Plans**: Installment options for students
- **Outstanding Fees Tracking**: Overdue fee alerts
- **Finance Reports**: Payment history and statistics

### Admin Dashboard
- **User Management**: Create, edit, deactivate user accounts
- **Program Management**: Add/edit programs and courses
- **Student Management**: View and manage student records
- **Admission Management**: Review and process applications
- **Finance Management**: Manage fees, payments, and receipts
- **Content Management**: News, events, gallery, downloads
- **Reports**: Financial and academic reports
- **Audit Logs**: Track all system activities

## Security Features

### Authentication & Authorization
- **Role-Based Access Control (RBAC)**: Admin, Staff, Student, Parent roles
- **Password Security**: bcrypt hashing with strong requirements
- **Session Management**: Secure, HTTP-only cookies
- **Two-Factor Authentication**: Optional 2FA for sensitive accounts
- **Email Verification**: Email confirmation for new accounts

### Data Security
- **Encryption**: Sensitive data encrypted using Laravel's encryption
- **SQL Injection Prevention**: Parameterized queries, ORM
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Token-based cross-site request forgery prevention
- **Rate Limiting**: API and form submission rate limiting

### Audit & Compliance
- **Audit Logs**: All user actions logged with timestamps and IP addresses
- **Data Privacy**: GDPR-compliant data handling
- **Backup Strategy**: Regular database backups
- **Access Logs**: Login and system access tracking

## Database Schema

### Core Tables
- `users` - All system users
- `students` - Student information
- `departments` - Academic departments
- `programs` - Academic programs
- `courses` - Course listings
- `course_enrollments` - Student course registrations
- `academic_sessions` - Academic calendar
- `examination_schedules` - Exam timetables

### Admission Tables
- `admissions` - Admission applications
- `admission_documents` - Application documents
- `admission_fees` - Application fees

### Finance Tables
- `school_fees` - Student fee schedules
- `fee_payments` - Payment records
- `payment_receipts` - Generated receipts
- `fee_schedules` - Program fee structures

### Hostel & Facilities
- `hostel_blocks` - Hostel buildings
- `hostel_rooms` - Individual rooms
- `hostel_allocations` - Student allocations
- `hostel_complaints` - Maintenance issues

### Content & Notifications
- `news` - News articles
- `events` - Events listings
- `gallery_albums` - Photo albums
- `notifications` - User notifications
- `audit_logs` - System activity logs

## Configuration Files

### `.env` Environment Variables
```env
APP_NAME=Munau College Management System
APP_ENV=production
APP_DEBUG=false
APP_URL=https://munaucollege.edu.ng

DB_CONNECTION=mysql
DB_HOST=your-rds-endpoint
DB_DATABASE=munau_college
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_FROM_ADDRESS=noreply@munaucollege.edu.ng

STRIPE_PUBLIC_KEY=your_stripe_key
PAYSTACK_PUBLIC_KEY=your_paystack_key

ENCRYPTION_KEY=base64:your_encryption_key
JWT_SECRET=your_jwt_secret

AUDIT_LOG_ENABLED=true
```

### Custom Configuration (`config/college.php`)
```php
return [
    'institution_name' => 'Munau College of Health Sciences and Technology',
    'country' => 'Nigeria',
    'admission_fee' => 5000,
    'academic_year' => 2024,
    'support_email' => 'support@munaucollege.edu.ng',
    'support_phone' => '+234XXXXXXXXX',
];
```

## AWS Deployment Guide

### Architecture
- **EC2**: Web server (Laravel application)
- **RDS**: MySQL database
- **S3**: File storage (documents, images)
- **CloudFront**: CDN for static assets
- **Route 53**: DNS management
- **SES**: Email service

### Deployment Steps

1. **EC2 Instance Setup**
   ```bash
   # SSH into EC2
   ssh -i your-key.pem ec2-user@your-instance-ip
   
   # Install dependencies
   sudo yum update -y
   sudo yum install php php-mysql apache2 composer git -y
   
   # Clone repository
   git clone <repo-url>
   cd munau-college-system
   
   # Install Laravel
   composer install --no-dev --optimize-autoloader
   ```

2. **Database Setup**
   - Create RDS MySQL instance
   - Update `.env` with RDS endpoint
   - Run migrations: `php artisan migrate --force`

3. **File Storage**
   - Configure S3 bucket in `.env`
   - Update `config/filesystems.php` for S3 driver
   - Run: `php artisan storage:link`

4. **Web Server Configuration**
   - Configure Apache/Nginx virtual host
   - Set document root to `public/`
   - Enable HTTPS with AWS Certificate Manager

5. **Environment Setup**
   - Set `.env` to production
   - Cache configuration: `php artisan config:cache`
   - Cache routes: `php artisan route:cache`

## Maintenance & Monitoring

### Regular Tasks
- Database backups (daily)
- Log file cleanup
- Security updates
- Database optimization

### Monitoring
- Server uptime monitoring
- Application error tracking (Sentry)
- Database performance monitoring
- API rate limit monitoring

## API Endpoints (Optional REST API)

If REST API is needed:
- `/api/programs` - GET all programs
- `/api/admissions` - POST new application
- `/api/students/{id}/fees` - GET student fees
- `/api/students/{id}/courses` - GET enrolled courses
- `/api/payments` - POST payment record

## Support & Troubleshooting

### Common Issues

**1. Database Connection Error**
- Verify `.env` database credentials
- Ensure RDS security group allows connection
- Check MySQL service is running

**2. File Upload Not Working**
- Verify storage permissions: `chmod 775 storage/`
- Check S3 credentials if using S3
- Verify max upload size in php.ini

**3. Email Not Sending**
- Verify MAIL_* credentials in `.env`
- Check spam folder
- Review mail logs: `tail -f storage/logs/laravel.log`

**4. Performance Issues**
- Clear cache: `php artisan cache:clear`
- Optimize database: `php artisan tinker`
- Enable query caching

## Contact & Support
- **Email**: support@munaucollege.edu.ng
- **Phone**: +234 (0) XXX XXX XXXX
- **Portal**: https://munaucollege.edu.ng

## License
Proprietary - Munau College of Health Sciences and Technology

---

**Last Updated**: January 2024
**Version**: 1.0.0
