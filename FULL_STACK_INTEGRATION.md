# Full Stack Integration Guide

## Munau College - Complete System Architecture

This document outlines how to integrate and deploy the complete Munau College Management System consisting of:

1. **Next.js Frontend** (React UI)
2. **Laravel Backend** (API + Database)
3. **MySQL Database**
4. **AWS Infrastructure** (Optional, for production)

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Client Layer                             │
│                   (Next.js Frontend)                         │
│  - Public Website  - Student Portal  - Admission System     │
│  - Finance Module  - Admin Dashboard                        │
└────────────────────────┬────────────────────────────────────┘
                         │ HTTPS/JSON API
┌────────────────────────▼────────────────────────────────────┐
│                   API Gateway Layer                          │
│                 (Laravel REST API)                           │
│  - Authentication  - Authorization  - Request Validation    │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│               Application Layer (Laravel)                    │
│  - Controllers  - Services  - Models  - Business Logic      │
│  - File Upload  - Email Notifications - PDF Generation     │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│              Data Access Layer (Eloquent ORM)               │
│  - Database Queries  - Relationships  - Migrations         │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│                  Data Layer (MySQL)                          │
│  - Users  - Students  - Admissions  - Finances            │
│  - Courses  - Results  - Hostel  - Audit Logs            │
└─────────────────────────────────────────────────────────────┘
```

## Local Development Setup

### Step 1: Clone/Extract Both Projects

```bash
# Create project directory
mkdir munau-college
cd munau-college

# Clone or extract frontend (Next.js)
cd frontend

# Clone or extract backend (Laravel)
cd ../backend
```

### Step 2: Backend Setup (Laravel)

```bash
cd backend

# Install dependencies
composer install

# Configure environment
cp .env.example .env

# Generate app key
php artisan key:generate

# Setup database
mysql -u root -p
CREATE DATABASE munau_college_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Update .env with database credentials
# DB_DATABASE=munau_college_db
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database (optional, for demo data)
php artisan db:seed

# Create storage link for uploads
php artisan storage:link

# Start backend server
php artisan serve --port=8000
```

The Laravel API will be available at `http://localhost:8000`

### Step 3: Frontend Setup (Next.js)

```bash
cd ../frontend

# Install dependencies
npm install

# Configure environment
cp frontend.env.example .env.local

# Update .env.local with backend URL
echo "NEXT_PUBLIC_API_URL=http://localhost:8000/api" >> .env.local

# Start development server
npm run dev
```

The Next.js frontend will be available at `http://localhost:3000`

## API Integration Examples

### Authentication Flow

#### 1. Login Request (Frontend)
```typescript
// app/auth/login/page.tsx
async function handleLogin(email: string, password: string) {
  const response = await fetch(
    `${process.env.NEXT_PUBLIC_API_URL}/auth/login`,
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include', // For cookies
      body: JSON.stringify({ email, password }),
    }
  );
  
  const data = await response.json();
  
  if (data.token) {
    localStorage.setItem('authToken', data.token);
    // Redirect to dashboard
  }
}
```

#### 2. Login Endpoint (Backend)
```php
// Laravel: app/Http/Controllers/AuthController.php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('auth')->plainTextToken;
        
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    return response()->json(['message' => 'Invalid credentials'], 401);
}
```

### Admission Application Submission

#### 1. Frontend Form Submission
```typescript
// app/admission/apply/page.tsx
async function submitApplication(formData) {
  const response = await fetch(
    `${process.env.NEXT_PUBLIC_API_URL}/admissions`,
    {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${authToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData),
    }
  );

  if (response.ok) {
    const data = await response.json();
    setApplicationId(data.application_id);
    setStep(4); // Success
  }
}
```

#### 2. Backend Admission Processing
```php
// Laravel: app/Http/Controllers/AdmissionController.php
public function store(StoreAdmissionRequest $request)
{
    $admission = Admission::create($request->validated());
    
    // Generate unique application ID
    $admission->application_id = 'APP-' . date('Y') . '-' . $admission->id;
    $admission->save();
    
    // Send confirmation email
    Mail::send(new AdmissionConfirmation($admission));
    
    // Log audit trail
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => 'admission_submitted',
        'resource' => 'Admission',
        'resource_id' => $admission->id,
    ]);
    
    return response()->json($admission, 201);
}
```

### Fee Payment Integration

#### 1. Initiate Payment (Frontend)
```typescript
// app/student/fees/page.tsx
async function initiatePayment(amount: number) {
  const response = await fetch(
    `${process.env.NEXT_PUBLIC_API_URL}/finance/payment/initialize`,
    {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${authToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ amount, description: 'School Fees' }),
    }
  );

  const data = await response.json();
  // Redirect to payment gateway
  window.location.href = data.payment_url;
}
```

#### 2. Payment Processing (Backend)
```php
// Laravel: app/Http/Controllers/FinanceController.php
public function initializePayment(Request $request)
{
    $student = auth()->user()->student;
    
    $payment = Payment::create([
        'student_id' => $student->id,
        'amount' => $request->amount,
        'status' => 'pending',
        'reference' => uniqid('PAY_'),
    ]);
    
    // Initialize with payment gateway
    $paymentUrl = $this->paymentService->initiate(
        $payment->amount,
        $payment->reference,
        $student->email
    );
    
    return response()->json(['payment_url' => $paymentUrl]);
}
```

## CORS Configuration

### Laravel Backend (.env)
```env
# Allow Next.js frontend to access API
APP_URL=http://localhost:8000
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
SESSION_DOMAIN=localhost
```

### Laravel Middleware (config/cors.php)
```php
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

## Database Schema Synchronization

The database is defined by Laravel migrations. After running migrations, the following tables are created:

- **users**: Authentication and core user data
- **students**: Student-specific information
- **admissions**: Application records
- **courses**: Course catalog
- **course_enrollments**: Student course registrations
- **school_fees**: Fee structure and payments
- **payments**: Payment transaction history
- **hostel_accommodations**: Hostel management
- **notifications**: System notifications
- **audit_logs**: Security audit trail

## File Upload Handling

### Frontend Upload
```typescript
// Student document upload
async function uploadDocument(file: File) {
  const formData = new FormData();
  formData.append('document', file);
  formData.append('type', 'transcript');

  const response = await fetch(
    `${process.env.NEXT_PUBLIC_API_URL}/admissions/${appId}/upload`,
    {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${authToken}` },
      body: formData,
    }
  );
}
```

### Backend Processing
```php
// Laravel: Handle file upload
public function uploadDocument(Request $request, $admissionId)
{
    $request->validate(['document' => 'required|file|max:5120']);
    
    $path = $request->file('document')
        ->store("admissions/{$admissionId}", 'private');
    
    AdmissionDocument::create([
        'admission_id' => $admissionId,
        'path' => $path,
        'type' => $request->type,
    ]);
    
    return response()->json(['message' => 'Document uploaded']);
}
```

## Production Deployment

### AWS Deployment Architecture

```
┌─────────────────────────────────────────┐
│           CloudFront CDN                 │
│         (Static content caching)         │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│    Application Load Balancer (ALB)      │
│     (Route to Next.js and Laravel)      │
└──────────────┬──────────────────────────┘
               │
      ┌────────┴────────┐
      │                 │
┌─────▼──────┐    ┌─────▼──────┐
│ EC2 (Next) │    │ EC2 (Laravel)
│ Auto Scaling   │ Auto Scaling
└─────┬──────┘    └─────┬──────┘
      │                 │
      │      ┌──────────┘
      │      │
┌─────▼──────▼──────┐
│  RDS MySQL        │
│  (Multi-AZ)       │
└──────────────────┘
```

### Environment Variables (Production)

#### Frontend (.env.production)
```env
NEXT_PUBLIC_API_URL=https://api.munaucollege.edu.ng/api
NEXT_PUBLIC_APP_URL=https://munaucollege.edu.ng
```

#### Backend (.env.production)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.munaucollege.edu.ng

DB_CONNECTION=mysql
DB_HOST=rds-mysql-endpoint
DB_DATABASE=munau_college_prod
DB_USERNAME=admin
DB_PASSWORD=strong_password

CORS_ALLOWED_ORIGINS=https://munaucollege.edu.ng
```

## Testing

### Frontend Testing
```bash
# Unit tests
npm test

# E2E tests
npm run test:e2e

# Build verification
npm run build
```

### Backend Testing
```bash
# Run test suite
php artisan test

# With coverage
php artisan test --coverage
```

## Monitoring & Logging

### Frontend (Sentry/LogRocket)
```typescript
import * as Sentry from "@sentry/nextjs";

Sentry.init({
  dsn: process.env.NEXT_PUBLIC_SENTRY_DSN,
  environment: process.env.NODE_ENV,
});
```

### Backend (Laravel Logging)
```php
// logs/laravel.log
Log::info('User login', ['user_id' => auth()->id()]);
Log::error('Payment failed', ['exception' => $e->getMessage()]);
```

## Performance Optimization

### Frontend
- Image optimization with Next.js Image
- Code splitting with dynamic imports
- CSS-in-JS with Tailwind CSS v4
- SWR for data fetching and caching

### Backend
- Query optimization with Eloquent eager loading
- API response caching with Redis
- Database indexing on frequently queried fields
- Pagination on list endpoints

## Security Checklist

- [ ] Enable HTTPS on all endpoints
- [ ] Implement rate limiting (API Gateway)
- [ ] Set strong CORS headers
- [ ] Use HTTP-only cookies for auth tokens
- [ ] Implement CSRF protection
- [ ] Enable SQL parameterization (Eloquent)
- [ ] Sanitize user input (Validation)
- [ ] Implement audit logging
- [ ] Regular security patches
- [ ] Database encryption at rest
- [ ] SSL/TLS certificates (Let's Encrypt)

## Troubleshooting

### CORS Error
```
Problem: "Cross-Origin Request Blocked"
Solution: Check CORS settings in Laravel config/cors.php
          Verify frontend URL in SANCTUM_STATEFUL_DOMAINS
```

### Authentication Failed
```
Problem: "Unauthorized" responses
Solution: Verify auth token is sent in Authorization header
          Check token expiration
          Verify auth middleware in routes
```

### Database Connection
```
Problem: "SQLSTATE[HY000]: General error"
Solution: Verify database credentials in .env
          Ensure MySQL service is running
          Check database exists and is accessible
```

## Continuous Integration/Deployment

### GitHub Actions Example
```yaml
# .github/workflows/deploy.yml
name: Deploy

on: [push]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Run tests
        run: npm test && php artisan test
      - name: Deploy to AWS
        run: ./deploy.sh
```

## Support & Documentation

- **Frontend Docs**: `/FRONTEND_README.md`
- **Backend Docs**: `/PROJECT_SETUP.md`, `/ARCHITECTURE.md`
- **API Documentation**: Available at `/api/docs` (Swagger)

---

**Last Updated**: 2024  
**Version**: 1.0.0  
**Status**: Production Ready
