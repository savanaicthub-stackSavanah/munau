# Admission with Credential Upload & Automatic Student Dashboard

## Overview

This feature enhances the admission process to include:
1. **Credential File Upload** - Students can upload academic transcripts, certificates, and birth certificates
2. **Password Creation** - Students create their own secure password during application
3. **Automatic Student Dashboard** - Upon successful submission, a student account and dashboard are automatically created
4. **Secure Account Setup** - Password validation, hashing, and secure session management

## System Architecture

### Frontend Flow

```
Admission Form (Step 3)
    ↓
[Password Creation + Document Upload]
    ↓
Validation (Password strength, file types)
    ↓
processAdmission() service
    ↓
API calls (Register, Admission, Dashboard)
    ↓
Success Screen with Credentials
    ↓
Redirect to Student Portal
```

### Backend Integration Points

The system integrates with the Laravel backend through these API endpoints:

1. **POST /api/auth/register**
   - Creates student user account
   - Hashes password with bcrypt
   - Returns student ID and auth token

2. **POST /api/admissions/create**
   - Creates admission record
   - Stores document URLs from cloud storage
   - Sets initial status to "SUBMITTED"
   - Creates audit log

3. **POST /api/student/dashboard/initialize**
   - Initializes dashboard data
   - Creates fee structure
   - Sets up course enrollment placeholder
   - Sends welcome notification

4. **POST /api/storage/upload** (Mock in Frontend)
   - Uploads documents to S3/cloud storage
   - Returns secure document URLs

## Features Implemented

### 1. Password Creation (Step 3)

**Validation Requirements:**
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- Passwords must match

**Security Features:**
- Client-side validation for UX
- Server-side validation for security
- Password hashing with bcrypt (backend)
- Secure session management

**User Feedback:**
```
✓ Real-time validation messages
✓ Clear error messages
✓ Password strength indicator
✓ Match confirmation
```

### 2. Document Upload

**Supported Files:**
- PDF documents
- JPG/JPEG images
- PNG images
- Maximum file size: 10MB (configurable)

**Required Documents:**
1. Academic Transcript
   - Official academic records
   - Required for admission validation

2. O'Level Certificate
   - WAEC, NECO, or equivalent
   - Minimum education requirement

3. Birth Certificate
   - Government-issued
   - Identity verification

**Upload Handling:**
```javascript
- Files stored locally on client during form completion
- Upon submission, uploaded to cloud storage (S3)
- URLs stored in admission record
- File access controlled by permissions
```

**Mock Upload Process (Frontend):**
```
Local File Selection
    ↓
Validate file type & size
    ↓
Store reference locally
    ↓
Display uploaded status
    ↓
On Submit: Upload to cloud
```

### 3. Automatic Student Dashboard Creation

When admission is submitted successfully:

**1. Student Account Created**
```json
{
  "studentId": "MN/2024/12345",
  "name": "John Doe",
  "email": "john@munau.edu.ng",
  "role": "student",
  "status": "APPLICANT",
  "program": "Nursing Science (RN)"
}
```

**2. Dashboard Initialized**
```json
{
  "gpa": 0,
  "creditsCompleted": 0,
  "creditsRequired": 120,
  "courses": [],
  "fees": {
    "tuition": 500000,
    "accommodation": 200000,
    "miscellaneous": 100000,
    "total": 800000,
    "paid": 0
  }
}
```

**3. Notifications Created**
- Welcome message
- Application status update
- Payment instructions

**4. Initial Data Structure**
- Empty timetable (waiting for enrollment)
- Fee breakdown (not paid yet)
- Course placeholder (ready for enrollment)
- Empty results (awaiting completion)

## File Structure

### Frontend Files

```
/app/
├── admission/
│   └── apply/page.tsx          # Enhanced admission form
├── api/
│   ├── auth/register/route.ts  # Student account creation
│   ├── admissions/create/route.ts
│   └── student/dashboard/initialize/route.ts
├── lib/
│   └── admission-service.ts    # Service layer for admission
├── student/
│   └── dashboard/page.tsx      # Student portal dashboard
└── auth/
    └── login/page.tsx          # Login to access dashboard
```

### Backend Files (Laravel)

```
/app/Http/Controllers/
├── AdmissionController.php     # Process admissions
└── StudentPortalController.php # Dashboard data

/app/Models/
├── Admission.php
├── Student.php
├── User.php
└── SchoolFee.php

/routes/
└── api.php                     # API endpoints

/database/migrations/
├── create_admissions_table.php
├── create_students_table.php
└── create_school_fees_table.php
```

## Step-by-Step Process

### User Journey

1. **Start Application**
   - User clicks "Apply Now" on homepage
   - Fills in Step 1: Personal Information

2. **Education Background** (Step 2)
   - Selects highest education level
   - Chooses preferred program
   - Reviews admission requirements

3. **Credentials & Documents** (Step 3) ← NEW
   - **Creates Password**
     - Enters password
     - Confirms password
     - System validates in real-time
   
   - **Uploads Documents**
     - Clicks upload area for transcript
     - Selects file from device
     - File is validated
     - Upload status shown
     - Repeats for all 3 required documents

4. **Submit Application**
   - Form validation triggers
   - Password is re-validated
   - Documents are uploaded to cloud
   - Student account is created
   - Application record is created
   - Dashboard is initialized
   - Success screen displays

5. **Success Screen** (Step 4) ← UPDATED
   - Shows application ID
   - Displays login credentials
   - Provides access instructions
   - Button to "Access Student Dashboard"

6. **Access Dashboard**
   - User is redirected to login page
   - Logs in with email and password
   - Directed to student dashboard
   - Completes any remaining profile info
   - Views fees, courses, timetable

## Code Examples

### Password Validation

```typescript
const validatePassword = () => {
  const password = formData.password;
  const confirmPassword = formData.confirmPassword;

  if (!password || !confirmPassword) {
    setPasswordError('Both password fields are required');
    return false;
  }

  if (password.length < 8) {
    setPasswordError('Password must be at least 8 characters long');
    return false;
  }

  if (!/(?=.*[a-z])/.test(password)) {
    setPasswordError('Password must contain at least one lowercase letter');
    return false;
  }

  if (!/(?=.*[A-Z])/.test(password)) {
    setPasswordError('Password must contain at least one uppercase letter');
    return false;
  }

  if (!/(?=.*\d)/.test(password)) {
    setPasswordError('Password must contain at least one number');
    return false;
  }

  if (password !== confirmPassword) {
    setPasswordError('Passwords do not match');
    return false;
  }

  setPasswordError('');
  return true;
};
```

### File Upload Handler

```typescript
const handleFileUpload = (doc: string, file: File | null) => {
  if (file) {
    // Validate file type
    const validTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    if (!validTypes.includes(file.type)) {
      setPasswordError('Only PDF, JPG, and PNG files are allowed');
      return;
    }

    // Validate file size (10MB max)
    if (file.size > 10 * 1024 * 1024) {
      setPasswordError('File must be less than 10MB');
      return;
    }

    setUploadedFiles((prev) => ({ ...prev, [doc]: file }));
    setUploaded((prev) => ({ ...prev, [doc]: true }));
  }
};
```

### Admission Processing

```typescript
const handleSubmit = async () => {
  if (!validatePassword()) {
    return;
  }

  if (!uploaded.transcript || !uploaded.certificate || !uploaded.birth) {
    alert('Please upload all required documents');
    return;
  }

  setLoading(true);
  try {
    // Process admission with service
    const { applicationId, studentId } = await processAdmission(
      {
        firstName: formData.firstName,
        lastName: formData.lastName,
        email: formData.email,
        phone: formData.phone,
        dateOfBirth: formData.dateOfBirth,
        program: formData.program,
        education: formData.education,
        password: formData.password,
      },
      uploadedFiles
    );

    // Store credentials for dashboard access
    localStorage.setItem('userEmail', formData.email);
    localStorage.setItem('studentId', studentId);
    localStorage.setItem('applicationId', applicationId);

    // Move to success step
    setStep(4);
  } catch (error) {
    console.error('Admission submission failed:', error);
    alert('Failed to submit application. Please try again.');
  } finally {
    setLoading(false);
  }
};
```

## Integration with Laravel Backend

### Register User Endpoint

```php
// Laravel: POST /api/auth/register
Route::post('/auth/register', [AuthController::class, 'register']);

// Controller method
public function register(Request $request)
{
    $validated = $request->validate([
        'studentId' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'name' => 'required',
        'phone' => 'required',
        'program' => 'required',
    ]);

    $user = User::create([
        'studentId' => $validated['studentId'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'name' => $validated['name'],
        'phone' => $validated['phone'],
        'program' => $validated['program'],
        'role' => 'student',
        'status' => 'APPLICANT',
    ]);

    return response()->json([
        'success' => true,
        'studentId' => $user->studentId,
        'email' => $user->email,
    ], 201);
}
```

### Create Admission Record

```php
// Laravel: POST /api/admissions/create
public function storeAdmission(Request $request)
{
    $validated = $request->validate([
        'applicationId' => 'required|unique:admissions',
        'studentId' => 'required|exists:users,studentId',
        'email' => 'required|email',
        'program' => 'required',
        'documentUrls' => 'required|array',
    ]);

    $admission = Admission::create([
        'applicationId' => $validated['applicationId'],
        'studentId' => $validated['studentId'],
        'email' => $validated['email'],
        'program' => $validated['program'],
        'document_urls' => $validated['documentUrls'],
        'status' => 'SUBMITTED',
        'submitted_at' => now(),
    ]);

    // Create audit log
    AuditLog::create([
        'action' => 'ADMISSION_SUBMITTED',
        'studentId' => $validated['studentId'],
        'details' => [
            'applicationId' => $validated['applicationId'],
            'program' => $validated['program'],
        ],
    ]);

    return response()->json([
        'success' => true,
        'applicationId' => $admission->applicationId,
    ], 201);
}
```

## Security Considerations

### Frontend
- Password validation with strength requirements
- File type and size validation
- CSRF protection
- Secure session storage
- No sensitive data in localStorage except email/ID

### Backend (Laravel)
- Password hashing with bcrypt
- Input validation and sanitization
- SQL injection prevention (Eloquent ORM)
- File upload validation and scanning
- Rate limiting on registration endpoints
- Audit logging of all actions
- Role-based access control
- HTTPS enforced

### Cloud Storage (S3)
- Pre-signed URLs for secure access
- Server-side encryption
- Access control policies
- Virus scanning on uploaded files
- Retention policies for archived documents

## Testing the Feature

### Manual Testing

1. **Test Password Validation**
   - Enter password less than 8 characters → Error shown
   - Enter password without uppercase → Error shown
   - Enter non-matching passwords → Error shown
   - Enter valid password → Success

2. **Test File Upload**
   - Try uploading invalid file type → Error shown
   - Try uploading large file (>10MB) → Error shown
   - Upload valid PDF/JPG/PNG → Success
   - Upload all 3 required documents → Button enabled

3. **Test Form Submission**
   - Submit with missing documents → Error
   - Submit with invalid password → Error
   - Submit complete form → Success screen with credentials

4. **Test Dashboard Access**
   - Use provided email/password to login
   - Verify dashboard loads with student data
   - Check that initial dashboard structure is created
   - Verify application ID is displayed

### Automated Testing

```typescript
// Example test cases
describe('Admission Form - Step 3', () => {
  it('should validate password strength', () => {
    // Test password requirements
  });

  it('should validate file uploads', () => {
    // Test file type validation
    // Test file size validation
  });

  it('should process admission and create account', () => {
    // Mock API calls
    // Test complete submission flow
  });

  it('should redirect to login after success', () => {
    // Test success screen
    // Test redirect functionality
  });
});
```

## Troubleshooting

### Common Issues

**Password Validation Failing**
- Ensure password has uppercase, lowercase, number
- Password must be at least 8 characters
- Passwords must match exactly

**File Upload Not Working**
- Check file format (PDF, JPG, PNG only)
- Check file size (under 10MB)
- Browser must allow file uploads
- Check CORS settings if using cloud storage

**Account Not Created**
- Check network tab for API errors
- Verify email is unique
- Check Laravel logs for database errors
- Ensure database migrations are run

**Can't Access Dashboard**
- Verify email and password used in login
- Check if account was created (check database)
- Clear browser cache and try again
- Check if user role is set to 'student'

## Future Enhancements

1. **Two-Factor Authentication**
   - SMS or email verification code
   - TOTP support for enhanced security

2. **Document Verification**
   - OCR for document validation
   - Automatic fraud detection
   - Integration with education board databases

3. **Email Notifications**
   - Confirmation email with credentials
   - Application status updates
   - Payment reminders
   - Interview invitations

4. **Dashboard Customization**
   - Custom dashboard widgets
   - Preference settings
   - Theme selection
   - Mobile app integration

5. **Payment Integration**
   - Direct payment from admission success page
   - Multiple payment options (card, transfer, USSD)
   - Payment history and receipts
   - Installment plans

## Support & Maintenance

### Monitoring
- Track admission completion rates
- Monitor form abandonment
- Track API response times
- Log all file uploads
- Monitor failed submissions

### Maintenance
- Regular security audits
- Password policy updates
- File upload quota management
- Database backup procedures
- Performance optimization

---

**Version:** 1.0  
**Last Updated:** 2024  
**Maintained By:** Development Team
