# Admission Feature - Quick Reference Guide

## What's New?

Students can now:
1. ✅ Create their own password during admission
2. ✅ Upload required credentials (transcript, certificate, birth certificate)
3. ✅ Automatically get a student account + dashboard upon submission

---

## Step-by-Step User Flow

### Step 1: Personal Information
```
First Name    → [    ]
Last Name     → [    ]
Email         → [    ]
Phone         → [    ]
Date of Birth → [    ]
```

### Step 2: Education Background
```
Education Level → [Dropdown: WAEC, UTME, Diploma, HND, Degree]
Preferred Program → [Dropdown: Nursing, Midwifery, MLS, etc.]
```

### Step 3: Create Password & Upload Documents ← NEW!

#### A. Create Password
```
Password         → [Password must be 8+ chars, have uppercase, lowercase, number]
Confirm Password → [Must match above]
✓ Real-time validation feedback
```

#### B. Upload Documents
```
📄 Academic Transcript
   └─ [Click to upload] → Select PDF/JPG/PNG → ✓ Uploaded

📄 O'Level Certificate  
   └─ [Click to upload] → Select PDF/JPG/PNG → ✓ Uploaded

📄 Birth Certificate
   └─ [Click to upload] → Select PDF/JPG/PNG → ✓ Uploaded
```

### Step 4: Success! You Have Access

```
✓ Application Submitted Successfully!

Your Application ID: APP-2024-...
Save this for your records!

---

Your Login Credentials:
Email:    student@munau.edu.ng
Password: YourPassword123

---

✓ Student Dashboard Created
You can now:
├─ View your application status
├─ Upload additional documents
├─ Track admission progress
├─ Pay application fee
└─ Receive notifications

Next Steps:
1) Pay application fee (₦2,500)
2) Wait for screening (2-3 weeks)
3) Check portal for admission letter

[Back to Home] [Access Student Dashboard]
```

---

## Password Requirements

Must have ALL of these:
- ✓ At least 8 characters
- ✓ At least 1 UPPERCASE letter (A-Z)
- ✓ At least 1 lowercase letter (a-z)
- ✓ At least 1 number (0-9)
- ✓ Passwords must match

### Examples:
```
❌ password123     → No uppercase
❌ PASSWORD123    → No lowercase
❌ Password      → No number
❌ Pass123       → Too short (7 chars)
✅ Password123   → Perfect!
```

---

## Document Upload Rules

### File Types Accepted:
```
✅ PDF documents (.pdf)
✅ Images (.jpg, .jpeg, .png)
❌ Word documents (.doc, .docx)
❌ Excel files (.xls, .xlsx)
❌ Text files (.txt)
```

### File Size:
```
✅ Up to 10 MB per file
❌ Larger than 10 MB
```

### Required Documents:
```
1. Academic Transcript
   - Official academic records
   - Shows all grades/courses taken

2. O'Level Certificate
   - WAEC, NECO, or equivalent
   - Proves secondary education completion

3. Birth Certificate
   - Government-issued
   - Proves identity & date of birth
```

---

## What Happens After Submission

### Immediate (Within seconds):
```
Your Application:
✓ Files uploaded to secure storage
✓ Application recorded in system
✓ Student account created
✓ Password encrypted and stored
✓ Dashboard initialized
✓ Confirmation email sent
```

### Your New Dashboard Includes:
```
Profile Section:
├─ Your name & ID
├─ Program enrolled in
└─ Contact information

Academics:
├─ Courses (empty, waiting for enrollment)
├─ Timetable (empty, waiting for schedule)
├─ Results (empty, awaiting grades)
└─ GPA: 0.0 (will update when you complete courses)

Finance:
├─ Fee breakdown
│  ├─ Tuition: ₦500,000
│  ├─ Accommodation: ₦200,000
│  └─ Miscellaneous: ₦100,000
├─ Total Outstanding: ₦800,000
├─ Amount Paid: ₦0
└─ Payment History (empty)

Admin:
├─ Application Status: SUBMITTED
├─ Admission Status: PENDING SCREENING
└─ Application ID: APP-2024-...
```

---

## Accessing Your Dashboard

### First Login:
1. Get your credentials from success screen
2. Go to `/auth/login`
3. Enter:
   - Email: your@email.com
   - Password: YourPassword123
4. Click "Login"
5. You're in your dashboard!

### Login Credentials:
```
Email:    Provided in application (same as you entered)
Password: What you created in Step 3
```

### Reset Password:
If you forget: Use "Forgot Password" link on login page

---

## Common Issues & Solutions

### "Password doesn't meet requirements"
```
Problem: Password validation failing
Solution: Check that password has:
  ✓ 8+ characters
  ✓ At least one UPPERCASE letter
  ✓ At least one lowercase letter  
  ✓ At least one number
  ✓ Passwords match
```

### "File upload failed"
```
Problem: Can't upload document
Solution: Check:
  ✓ File is PDF, JPG, or PNG
  ✓ File is less than 10 MB
  ✓ Browser allows file uploads
  ✓ Internet connection is stable
```

### "Can't login to dashboard"
```
Problem: Login not working
Solution:
  ✓ Use EXACT email from application
  ✓ Use EXACT password you created
  ✓ Check Caps Lock is off
  ✓ Clear browser cache and try again
```

### "Dashboard won't load"
```
Problem: Portal page is blank/broken
Solution:
  ✓ Refresh the page (F5)
  ✓ Clear browser cache
  ✓ Try different browser
  ✓ Check internet connection
```

---

## Important Notes

### Security
- ✅ Your password is encrypted (hashed)
- ✅ Only you can access your account
- ✅ Documents are securely stored
- ✅ Don't share your password

### Application Process
1. Application submitted ← You are here
2. Screening (2-3 weeks)
3. Admission letter sent
4. Pay acceptance fee
5. Official admission
6. Course enrollment begins

### Fees
- Application fee: ₦2,500 (pay within 48 hours)
- Acceptance fee: TBD (if admitted)
- School fees: ₦800,000/year (tuition + accommodation + misc)

---

## Support

### Still Have Questions?

**Documentation:**
- Read `/ADMISSION_WITH_ONBOARDING.md` for technical details
- Read `/FEATURES_ADDED.md` for feature overview

**Browser Console:**
- Open DevTools (F12)
- Check Console tab for error messages
- Take screenshot and send to support

**Contact:**
- Email: admissions@munau.edu.ng
- Phone: +234 (0) XXX XXXX XXXX
- Portal: Submit support ticket in dashboard

---

## Technical Details (For Developers)

### Architecture:
```
Frontend (Next.js)
    ↓
Admission Service (/app/lib/admission-service.ts)
    ├→ POST /api/auth/register
    ├→ POST /api/admissions/create
    └→ POST /api/student/dashboard/initialize
    ↓
Laravel Backend
    ├→ Users Database
    ├→ Admissions Database
    ├→ SchoolFees Database
    └→ Cloud Storage (S3)
```

### Files:
```
Frontend:
/app/admission/apply/page.tsx     - Form UI
/app/lib/admission-service.ts     - Service layer
/app/api/auth/register/route.ts   - Register API
/app/api/admissions/create/route.ts - Admission API
/app/api/student/dashboard/initialize/route.ts - Dashboard API

Backend:
/routes/api.php                   - Routes
/app/Http/Controllers/AdmissionController.php
/app/Models/Admission.php
/app/Models/User.php
/database/migrations/..._admissions_table.php
```

### Environment:
```
Frontend: Next.js 16, React 19.2, TypeScript
Backend: Laravel 11, MySQL 8
Storage: AWS S3 (or similar)
Email: SendGrid/SES (optional)
```

---

**Ready to get started? Navigate to `/admission/apply` and begin!**

**Version:** 1.0 | **Status:** Live ✅
