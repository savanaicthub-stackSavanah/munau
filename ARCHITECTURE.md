# Munau College Management System - Architecture Overview

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────┐  │
│  │  Public Website  │  │  Student Portal  │  │ Admin Panel  │  │
│  │  (Bootstrap 5)   │  │  (Bootstrap 5)   │  │(Bootstrap 5) │  │
│  └────────┬─────────┘  └────────┬─────────┘  └──────┬───────┘  │
│           │                      │                    │          │
│           └──────────────────────┼────────────────────┘          │
│                                  │                               │
└──────────────────────────────────┼───────────────────────────────┘
                                   │ HTTP/HTTPS
                    ┌──────────────┴──────────────┐
                    │    LARAVEL WEB SERVER      │
                    │  (PHP 8.2+ on Apache/Nginx)│
                    └──────────────┬──────────────┘
                                   │
┌──────────────────────────────────┼───────────────────────────────┐
│                    APPLICATION LAYER                             │
├──────────────────────────────────┼───────────────────────────────┤
│                                  │                               │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │                    ROUTING LAYER                           │ │
│  │  routes/web.php (80+ routes, 5 route groups)             │ │
│  └──────────────┬─────────────────────────────────────────────┘ │
│                 │                                               │
│  ┌──────────────┴──────────────────────────────────────────────┐│
│  │                  MIDDLEWARE LAYER                           ││
│  │ ┌─────────────────────────────────────────────────────────┐││
│  │ │ • Authentication (verified user)                        │││
│  │ │ • Student Middleware (role verification)               │││
│  │ │ • Admin Middleware (permission checking)               │││
│  │ │ • CSRF Protection (token validation)                   │││
│  │ │ • Guest Middleware (non-logged-in users)               │││
│  │ └─────────────────────────────────────────────────────────┘││
│  └─────────────────┬──────────────────────────────────────────┘│
│                    │                                            │
│  ┌─────────────────┴──────────────────────────────────────────┐│
│  │               CONTROLLER LAYER                             ││
│  │ ┌──────────────────────────────────────────────────────┐  ││
│  │ │ WebController                                        │  ││
│  │ │ • home(), about(), programs(), contact(), etc.      │  ││
│  │ └──────────────────────────────────────────────────────┘  ││
│  │ ┌──────────────────────────────────────────────────────┐  ││
│  │ │ AuthController                                       │  ││
│  │ │ • login(), register(), logout(), resetPassword()    │  ││
│  │ └──────────────────────────────────────────────────────┘  ││
│  │ ┌──────────────────────────────────────────────────────┐  ││
│  │ │ StudentPortalController                             │  ││
│  │ │ • dashboard(), courses(), results(), fees(), etc.   │  ││
│  │ └──────────────────────────────────────────────────────┘  ││
│  │ ┌──────────────────────────────────────────────────────┐  ││
│  │ │ AdmissionController                                 │  ││
│  │ │ • apply(), show(), uploadDocument(), tracking()     │  ││
│  │ └──────────────────────────────────────────────────────┘  ││
│  │ ┌──────────────────────────────────────────────────────┐  ││
│  │ │ Admin\*Controller (8+ controllers)                   │  ││
│  │ │ • Dashboard, Users, Admissions, Students, Courses   │  ││
│  │ └──────────────────────────────────────────────────────┘  ││
│  └──────────────┬───────────────────────────────────────────┘│
│                 │                                             │
│  ┌──────────────┴──────────────────────────────────────────┐ │
│  │              SERVICE LAYER                              │ │
│  │ ┌────────────────────────────────────────────────────┐ │ │
│  │ │ AuthenticationService                              │ │ │
│  │ │ • register(), authenticate(), changePassword()     │ │ │
│  │ │ • enableTwoFactor(), resetPassword()               │ │ │
│  │ └────────────────────────────────────────────────────┘ │ │
│  │ ┌────────────────────────────────────────────────────┐ │ │
│  │ │ FinanceService                                     │ │ │
│  │ │ • generateSchoolFee(), recordPayment()             │ │ │
│  │ │ • generatePaymentReceipt(), getOutstandingFees()   │ │ │
│  │ └────────────────────────────────────────────────────┘ │ │
│  │ ┌────────────────────────────────────────────────────┐ │ │
│  │ │ AdmissionService                                   │ │ │
│  │ │ • createApplication(), uploadDocument()            │ │ │
│  │ │ • screenApplication(), acceptAdmission()           │ │ │
│  │ │ • generateAdmissionLetter()                         │ │ │
│  │ └────────────────────────────────────────────────────┘ │ │
│  │ ┌────────────────────────────────────────────────────┐ │ │
│  │ │ NotificationService                                │ │ │
│  │ │ • notifyAdmissionSubmitted(), .Approved(), etc.    │ │ │
│  │ │ • notifyPaymentReceived(), notifyExamSchedule()    │ │ │
│  │ └────────────────────────────────────────────────────┘ │ │
│  └──────────────┬───────────────────────────────────────────┘ │
│                 │                                             │
│  ┌──────────────┴──────────────────────────────────────────┐ │
│  │            MODEL & ELOQUENT LAYER                       │ │
│  │ ┌────────────────────────────────────────────────────┐ │ │
│  │ │ User Model (with relationships)                    │ │ │
│  │ │ ├── Student (HasOne)                               │ │ │
│  │ │ ├── Notifications (HasMany)                        │ │ │
│  │ │ └── AuditLogs (HasMany)                            │ │ │
│  │ └────────────────────────────────────────────────────┘ │ │
│  │ ┌────────────────────────────────────────────────────┐ │ │
│  │ │ Student Model                                      │ │ │
│  │ │ ├── User (BelongsTo)                               │ │ │
│  │ │ ├── CourseEnrollments (HasMany)                    │ │ │
│  │ │ ├── SchoolFees (HasMany)                           │ │ │
│  │ │ └── HostelAllocations (HasMany)                    │ │ │
│  │ └────────────────────────────────────────────────────┘ │ │
│  │ ┌────────────────────────────────────────────────────┐ │ │
│  │ │ Program Model                                      │ │ │
│  │ │ ├── Department (BelongsTo)                         │ │ │
│  │ │ ├── Courses (HasMany)                              │ │ │
│  │ │ ├── Admissions (HasMany)                           │ │ │
│  │ │ └── FeeSchedules (HasMany)                         │ │ │
│  │ └────────────────────────────────────────────────────┘ │ │
│  │ ┌────────────────────────────────────────────────────┐ │ │
│  │ │ Admission, Course, SchoolFee, etc. Models (10+)    │ │ │
│  │ └────────────────────────────────────────────────────┘ │ │
│  └──────────────┬───────────────────────────────────────────┘ │
│                 │                                             │
└─────────────────┼─────────────────────────────────────────────┘
                  │
┌─────────────────┴─────────────────────────────────────────────┐
│               DATA PERSISTENCE LAYER                          │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │          MySQL Database (21 Tables)                  │   │
│  │ ┌──────────────────────────────────────────────────┐ │   │
│  │ │ Core: users, students, departments, programs    │ │   │
│  │ │ Academics: courses, enrollments, sessions       │ │   │
│  │ │ Admissions: admissions, documents, fees         │ │   │
│  │ │ Finance: school_fees, payments, receipts        │ │   │
│  │ │ Facilities: hostels, rooms, allocations         │ │   │
│  │ │ System: notifications, audit_logs               │ │   │
│  │ └──────────────────────────────────────────────────┘ │   │
│  └──────────────┬───────────────────────────────────────┘   │
│                 │                                             │
│  ┌──────────────┴──────────────────────────────────────┐    │
│  │   File Storage (Local/S3 Ready)                     │    │
│  │ • Document uploads (admissions)                     │    │
│  │ • Receipt PDFs (finance)                            │    │
│  │ • Student photos (profiles)                         │    │
│  │ • Gallery images (public site)                      │    │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐    │
│  │   Cache Layer (Redis/File Ready)                     │    │
│  │ • Session storage                                    │    │
│  │ • Configuration caching                             │    │
│  │ • Query result caching                              │    │
│  └──────────────────────────────────────────────────────┘    │
│                                                               │
└──────────────────────────────────────────────────────────────┘

                     ↓

┌──────────────────────────────────────────────────────────────┐
│            EXTERNAL SERVICES & INTEGRATIONS                  │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Payment Gateways (Framework Ready)                     │ │
│  │ • Paystack API (Nigeria payments)                      │ │
│  │ • Stripe API (International payments)                  │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Email Services (SMTP/AWS SES)                          │ │
│  │ • Password resets                                      │ │
│  │ • Application notifications                           │ │
│  │ • Payment confirmations                               │ │
│  │ • Academic updates                                    │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ PDF Generation (Framework Ready)                       │ │
│  │ • Admission letters                                    │ │
│  │ • Payment receipts                                     │ │
│  │ • Transcripts                                          │ │
│  │ • ID cards                                             │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Cloud Services (AWS)                                   │ │
│  │ • EC2 (Web server)                                     │ │
│  │ • RDS (Database)                                       │ │
│  │ • S3 (File storage)                                    │ │
│  │ • CloudFront (CDN)                                     │ │
│  │ • SES (Email)                                          │ │
│  │ • Route 53 (DNS)                                       │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

---

## Module Architecture

### 1. Public Website Module
```
┌─ Home Page
│   ├─ Featured Programs (dynamic)
│   ├─ Latest News (dynamic)
│   └─ Upcoming Events (dynamic)
│
├─ About Page
│   ├─ Vision/Mission
│   ├─ Core Values
│   ├─ History Timeline
│   └─ Statistics
│
├─ Academics
│   ├─ Programs Catalog (searchable)
│   ├─ Departments Listing
│   └─ Admission Requirements
│
├─ Information
│   ├─ News (with full-text search)
│   ├─ Events (registration ready)
│   ├─ Gallery (album organization)
│   └─ Downloads (organized by category)
│
└─ Engagement
    ├─ Contact Form (with email)
    ├─ Google Maps Integration
    ├─ Management Profiles
    └─ Governing Council
```

### 2. Student Portal Module
```
┌─ Authentication
│   ├─ Login (email/password)
│   ├─ Register (create student account)
│   ├─ Password Reset (email verification)
│   └─ 2FA (optional)
│
├─ Dashboard
│   ├─ Quick Stats (courses, fees, CGPA, notifications)
│   ├─ Quick Actions (register, pay, view results)
│   ├─ Important Deadlines
│   └─ Recent Activity Feed
│
├─ Academic Management
│   ├─ Course Registration (with prerequisites)
│   ├─ Course Drop (with validation)
│   ├─ Timetable Display
│   ├─ Exam Schedule (per course)
│   ├─ Results View (per course)
│   ├─ Transcript (downloadable)
│   └─ GPA Calculation
│
├─ Finance Management
│   ├─ Fee Structure View
│   ├─ Outstanding Fees Display
│   ├─ Payment History
│   ├─ Receipt Download
│   └─ Payment Status Tracking
│
├─ Facilities Management
│   ├─ Hostel Application
│   ├─ Room Allocation Display
│   ├─ Check-in/Check-out
│   ├─ Complaint Submission
│   └─ ID Card Request
│
└─ Notifications
    ├─ Academic Notifications
    ├─ Finance Alerts
    ├─ Hostel Updates
    ├─ Admission Updates
    └─ Mark as Read/Archive
```

### 3. Admission Module
```
┌─ Application Process
│   ├─ Multi-step Form
│   │   ├─ Personal Information
│   │   ├─ Contact Details
│   │   ├─ Academic Background
│   │   └─ Program Selection
│   │
│   ├─ Document Upload
│   │   ├─ Birth Certificate
│   │   ├─ O'Level Result
│   │   ├─ Passport Photo
│   │   └─ Supporting Documents
│   │
│   ├─ Payment Integration
│   │   ├─ Admission Fee Calculation
│   │   ├─ Payment Link Generation
│   │   └─ Payment Status Tracking
│   │
│   └─ Status Tracking
│       ├─ Application Number
│       ├─ Current Status
│       └─ Timeline View
│
├─ Admin Processing
│   ├─ Application Review
│   ├─ Document Verification
│   ├─ Candidate Screening
│   ├─ Shortlisting
│   ├─ Interview Scheduling
│   └─ Admission Decision
│
└─ Student Onboarding
    ├─ Acceptance Email
    ├─ Automatic User Account Creation
    ├─ Student Record Creation
    ├─ Matric Number Generation
    └─ Portal Access Grant
```

### 4. Finance Module
```
┌─ Fee Structure
│   ├─ Program-Level Mapping
│   ├─ Component Breakdown
│   │   ├─ Tuition Fee
│   │   ├─ Acceptance Fee
│   │   ├─ Registration Fee
│   │   ├─ Facilities Fee
│   │   ├─ Technology Fee
│   │   └─ Other Charges
│   │
│   ├─ Academic Session Binding
│   └─ Effective Date Management
│
├─ Fee Generation
│   ├─ Automatic Calculation
│   ├─ Student Assignment
│   ├─ Balance Computation
│   └─ Due Date Setting
│
├─ Payment Processing
│   ├─ Multiple Payment Methods
│   │   ├─ Bank Transfer
│   │   ├─ Card Payment
│   │   ├─ Paystack
│   │   └─ Stripe
│   │
│   ├─ Payment Recording
│   ├─ Reference Generation
│   ├─ Verification Workflow
│   └─ Status Updating
│
├─ Receipt Management
│   ├─ Auto-Generation
│   ├─ Unique Numbering
│   ├─ PDF Format
│   ├─ Download Capability
│   └─ Print Tracking
│
└─ Reporting & Analytics
    ├─ Outstanding Fees Report
    ├─ Payment History
    ├─ Revenue Analysis
    └─ Student Account Status
```

---

## Data Flow Diagram

### Admission Flow
```
Applicant → Apply Form → Validation → Database
    ↓
Create Application Record
    ↓
Upload Documents → Verification → Storage
    ↓
Pay Admission Fee → Payment Gateway → Record Payment
    ↓
Submit Application → Notification Email
    ↓
Admin Review → Screening → Shortlisting
    ↓
Interview → Admission Decision
    ↓
Generate Letter → Send to Applicant
    ↓
Acceptance → Create Student Account → Create Student Record
    ↓
Access Student Portal
```

### Finance Flow
```
Student Account → Automatic Fee Generation
    ↓
Fee Record Created (with balance)
    ↓
Student Views Outstanding Balance
    ↓
Initiate Payment → Choose Method
    ↓
Payment Gateway (Paystack/Stripe)
    ↓
Payment Success → Record Transaction
    ↓
Update Fee Balance
    ↓
Generate Receipt → Send Email
    ↓
Student Downloads Receipt → Update Status
```

### Course Registration Flow
```
Student → View Available Courses
    ↓
Check Prerequisites
    ↓
Register Course → Validation
    ↓
Create Enrollment Record
    ↓
Update Course Capacity
    ↓
Notification to Student
    ↓
Display in Dashboard
    ↓
View Timetable → Exam Schedule
```

---

## Security Architecture

```
┌─────────────────────────────────────────────────────────┐
│              SECURITY LAYERS                            │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 1. HTTP Security                                  │  │
│  │ • HTTPS/SSL encryption                            │  │
│  │ • Security headers (HSTS, CSP, X-Frame-Options)  │  │
│  │ • CORS protection                                 │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 2. Authentication Security                        │  │
│  │ • bcrypt password hashing                         │  │
│  │ • Session token management                        │  │
│  │ • HTTP-only cookies                               │  │
│  │ • CSRF token validation                           │  │
│  │ • Two-factor authentication (optional)            │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 3. Authorization & Access Control                │  │
│  │ • Role-based access control (RBAC)               │  │
│  │ • Middleware permission checks                    │  │
│  │ • Resource-level authorization                   │  │
│  │ • Data isolation per user                         │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 4. Input Validation & Sanitization              │  │
│  │ • Server-side validation (Laravel)               │  │
│  │ • Type casting (Eloquent)                         │  │
│  │ • Whitelist validation                            │  │
│  │ • File upload validation (type, size)            │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 5. SQL Injection Prevention                      │  │
│  │ • Parameterized queries (Eloquent ORM)          │  │
│  │ • Prepared statements                            │  │
│  │ • No dynamic query construction                   │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 6. XSS & Injection Protection                   │  │
│  │ • Output escaping in Blade ({{ }})              │  │
│  │ • HTML entity encoding                           │  │
│  │ • Content Security Policy headers                │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 7. Data Encryption                              │  │
│  │ • Laravel encryption for sensitive fields       │  │
│  │ • Database-level encryption (optional)           │  │
│  │ • Secure session storage                         │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 8. Audit & Compliance                           │  │
│  │ • Complete action logging                        │  │
│  │ • Timestamp recording                            │  │
│  │ • IP address tracking                            │  │
│  │ • Change history (old/new values)               │  │
│  │ • Login attempt logging                          │  │
│  │ • Admin action tracking                          │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 9. Rate Limiting & DoS Protection               │  │
│  │ • Login attempt throttling                       │  │
│  │ • API rate limiting (if REST API)                │  │
│  │ • Form submission limiting                       │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │ 10. File Security                               │  │
│  │ • File upload validation                         │  │
│  │ • Mime type checking                             │  │
│  │ • Size limitation (5MB max)                      │  │
│  │ • Secure storage paths                           │  │
│  │ • Download permission checking                   │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## Database Relationships

```
users (1) ──────────────── (1) students
users (1) ──────────────── (Many) notifications
users (1) ──────────────── (Many) audit_logs

students (1) ────────────── (Many) course_enrollments
students (1) ────────────── (Many) school_fees
students (1) ────────────── (Many) hostel_allocations
students (1) ────────────── (Many) id_card_requests

programs (1) ────────────── (Many) students
programs (1) ────────────── (Many) admissions
programs (1) ────────────── (Many) courses
programs (1) ────────────── (Many) fee_schedules

departments (1) ──────────── (Many) programs
departments (1) ──────────── (Many) courses

courses (1) ────────────── (Many) course_enrollments
courses (1) ────────────── (Many) examination_schedules

admissions (1) ──────────── (Many) admission_documents
admissions (1) ──────────── (Many) admission_fees

school_fees (1) ─────────── (Many) fee_payments
school_fees (1) ─────────── (Many) payment_receipts

academic_sessions (1) ────── (Many) course_enrollments
academic_sessions (1) ────── (Many) school_fees
academic_sessions (1) ────── (Many) examination_schedules

hostel_blocks (1) ────────── (Many) hostel_rooms
hostel_rooms (1) ──────────── (Many) hostel_allocations
```

---

## Deployment Architecture (AWS)

```
┌────────────────────────────────────────────────────┐
│              AWS REGION (us-east-1)                │
├────────────────────────────────────────────────────┤
│                                                     │
│  ┌──────────────────────────────────────────────┐ │
│  │ CloudFront CDN                                │ │
│  │ (Static assets, images, CSS, JS)             │ │
│  └──────────────┬───────────────────────────────┘ │
│                 │                                  │
│  ┌──────────────┴───────────────────────────────┐ │
│  │ Application Load Balancer (ALB)              │ │
│  │ (HTTPS, automatic scaling)                   │ │
│  └──────────────┬───────────────────────────────┘ │
│                 │                                  │
│  ┌──────────────┴───────────────────────────────┐ │
│  │ Auto Scaling Group (EC2 Instances)           │ │
│  │ ├─ Instance 1 (PHP 8.2, Nginx/Apache)       │ │
│  │ ├─ Instance 2 (PHP 8.2, Nginx/Apache)       │ │
│  │ └─ Instance N (Scaled based on load)         │ │
│  └──────────────┬───────────────────────────────┘ │
│                 │                                  │
│  ┌──────────────┴───────────────────────────────┐ │
│  │ ElastiCache (Redis)                           │ │
│  │ (Session storage, caching)                   │ │
│  └───────────────────────────────────────────────┘ │
│                 │                                  │
│  ┌──────────────┴───────────────────────────────┐ │
│  │ RDS MySQL (Multi-AZ)                         │ │
│  │ (Primary DB with automatic backups)          │ │
│  └───────────────────────────────────────────────┘ │
│                                                     │
│  ┌──────────────────────────────────────────────┐ │
│  │ S3 Bucket                                     │ │
│  │ (Document uploads, receipts, images)         │ │
│  └───────────────────────────────────────────────┘ │
│                                                     │
│  ┌──────────────────────────────────────────────┐ │
│  │ SES (Email Service)                           │ │
│  │ (Notifications, password resets)             │ │
│  └───────────────────────────────────────────────┘ │
│                                                     │
│  ┌──────────────────────────────────────────────┐ │
│  │ CloudWatch                                    │ │
│  │ (Monitoring, logging, alerting)              │ │
│  └───────────────────────────────────────────────┘ │
│                                                     │
│  ┌──────────────────────────────────────────────┐ │
│  │ Route 53                                      │ │
│  │ (DNS, domain management)                     │ │
│  └───────────────────────────────────────────────┘ │
│                                                     │
└────────────────────────────────────────────────────┘
```

---

## Performance Optimization Strategy

```
┌─ Database Optimization
│  ├─ Indexed columns
│  ├─ Query optimization (N+1 solution with eager loading)
│  ├─ Caching frequently accessed data
│  └─ Database connection pooling
│
├─ Application Caching
│  ├─ Config caching (php artisan config:cache)
│  ├─ Route caching (php artisan route:cache)
│  ├─ View caching (compiled Blade templates)
│  └─ Redis caching for sessions and data
│
├─ Frontend Optimization
│  ├─ CSS minification
│  ├─ JavaScript bundling and minification
│  ├─ Image optimization (responsive, WebP)
│  ├─ CDN for static assets (CloudFront)
│  └─ Lazy loading for images
│
├─ Server Optimization
│  ├─ gzip compression
│  ├─ HTTP/2 protocol
│  ├─ Browser caching headers
│  └─ Auto-scaling based on load
│
└─ Monitoring & Profiling
   ├─ Query logging for slow queries
   ├─ Application performance monitoring (Sentry)
   ├─ Server metrics (CloudWatch)
   └─ User experience tracking (WebVitals)
```

---

**Version**: 1.0.0
**Date**: January 2024
**Status**: Architecture Complete & Implemented
