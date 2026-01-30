<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\CourseEnrollment;
use App\Models\SchoolFee;
use App\Models\FeePayment;
use App\Models\AcademicSession;
use App\Models\Notification;
use App\Models\HostelAllocation;
use App\Models\IdCardRequest;
use Illuminate\Http\Request;
use Auth;

class StudentPortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('student');
    }

    // Dashboard
    public function dashboard()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('student.profile')->with('error', 'Student profile not found. Please complete your profile.');
        }

        $currentSession = AcademicSession::where('is_current', true)->first();
        
        $enrolledCourses = CourseEnrollment::where('student_id', $student->id)
            ->where('academic_session_id', $currentSession->id ?? null)
            ->count();

        $schoolFee = SchoolFee::where('student_id', $student->id)
            ->where('academic_session_id', $currentSession->id ?? null)
            ->first();

        $pendingNotifications = Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->count();

        $hostelAllocation = HostelAllocation::where('student_id', $student->id)
            ->where('academic_session_id', $currentSession->id ?? null)
            ->first();

        return view('student.dashboard', compact('student', 'enrolledCourses', 'schoolFee', 'pendingNotifications', 'hostelAllocation'));
    }

    // Profile
    public function profile()
    {
        $user = Auth::user();
        $student = $user->student;
        
        return view('student.profile', compact('user', 'student'));
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $validated = $request->validate([
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:3',
            'next_of_kin_name' => 'nullable|string|max:255',
            'next_of_kin_phone' => 'nullable|string|max:20',
            'next_of_kin_relationship' => 'nullable|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update user profile
        $user->update($validated);

        // Update student profile if student record exists
        if ($student) {
            $student->update($validated);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->update(['profile_photo' => $path]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    // Courses
    public function courses()
    {
        $student = Auth::user()->student;
        $currentSession = AcademicSession::where('is_current', true)->first();

        $enrolledCourses = CourseEnrollment::where('student_id', $student->id)
            ->where('academic_session_id', $currentSession->id ?? null)
            ->with('course')
            ->paginate(10);

        $availableCourses = \App\Models\Course::where('level', $student->current_level)
            ->whereDoesntHave('enrollments', function($q) use ($student, $currentSession) {
                $q->where('student_id', $student->id)
                  ->where('academic_session_id', $currentSession->id ?? null);
            })
            ->paginate(10);

        return view('student.courses', compact('enrolledCourses', 'availableCourses', 'currentSession'));
    }

    // Register Course
    public function registerCourse($id)
    {
        $student = Auth::user()->student;
        $currentSession = AcademicSession::where('is_current', true)->first();
        $course = \App\Models\Course::findOrFail($id);

        // Check if already enrolled
        $exists = CourseEnrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('academic_session_id', $currentSession->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        CourseEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'academic_session_id' => $currentSession->id,
            'status' => 'active',
            'enrollment_date' => now(),
        ]);

        return back()->with('success', 'Course registered successfully.');
    }

    // Drop Course
    public function dropCourse($id)
    {
        $student = Auth::user()->student;
        $enrollment = CourseEnrollment::where('student_id', $student->id)
            ->where('id', $id)
            ->firstOrFail();

        $enrollment->update(['status' => 'dropped']);

        return back()->with('success', 'Course dropped successfully.');
    }

    // Timetable
    public function timetable()
    {
        $student = Auth::user()->student;
        $currentSession = AcademicSession::where('is_current', true)->first();

        $enrolledCourses = CourseEnrollment::where('student_id', $student->id)
            ->where('academic_session_id', $currentSession->id)
            ->where('status', 'active')
            ->with('course')
            ->get();

        return view('student.timetable', compact('enrolledCourses', 'currentSession'));
    }

    // Exam Schedule
    public function examSchedule()
    {
        $student = Auth::user()->student;
        $currentSession = AcademicSession::where('is_current', true)->first();

        $examSchedules = \App\Models\ExaminationSchedule::whereIn('course_id', function($q) use ($student, $currentSession) {
            $q->select('course_id')
              ->from('course_enrollments')
              ->where('student_id', $student->id)
              ->where('academic_session_id', $currentSession->id);
        })
        ->where('academic_session_id', $currentSession->id)
        ->orderBy('exam_date_time')
        ->get();

        return view('student.exam-schedule', compact('examSchedules', 'currentSession'));
    }

    // Results
    public function results()
    {
        $student = Auth::user()->student;

        $results = CourseEnrollment::where('student_id', $student->id)
            ->whereNotNull('score')
            ->with('course')
            ->paginate(10);

        return view('student.results', compact('results'));
    }

    // Transcript
    public function transcript()
    {
        $student = Auth::user()->student;

        $coursesBySession = CourseEnrollment::where('student_id', $student->id)
            ->whereNotNull('score')
            ->with(['course', 'academicSession'])
            ->get()
            ->groupBy('academic_session_id');

        return view('student.transcript', compact('student', 'coursesBySession'));
    }

    // Finance Dashboard
    public function finances()
    {
        $student = Auth::user()->student;
        $currentSession = AcademicSession::where('is_current', true)->first();

        $schoolFee = SchoolFee::where('student_id', $student->id)
            ->where('academic_session_id', $currentSession->id)
            ->first();

        $recentPayments = FeePayment::where('student_id', $student->id)
            ->where('payment_status', 'completed')
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        return view('student.finances', compact('schoolFee', 'recentPayments', 'currentSession'));
    }

    // Fees
    public function fees()
    {
        $student = Auth::user()->student;
        $fees = SchoolFee::where('student_id', $student->id)->paginate(10);

        return view('student.fees', compact('fees'));
    }

    // Pay Fee
    public function payFee($id)
    {
        $student = Auth::user()->student;
        $schoolFee = SchoolFee::where('student_id', $student->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('student.pay-fee', compact('schoolFee'));
    }

    // Receipts
    public function receipts()
    {
        $receipts = \App\Models\PaymentReceipt::where('student_id', Auth::user()->student->id)
            ->orderBy('generated_at', 'desc')
            ->paginate(10);

        return view('student.receipts', compact('receipts'));
    }

    // Download Receipt
    public function downloadReceipt($id)
    {
        $receipt = \App\Models\PaymentReceipt::where('student_id', Auth::user()->student->id)
            ->findOrFail($id);

        // Generate PDF and download
        // This would typically use a PDF library
        return response()->download("storage/receipts/{$receipt->receipt_pdf_path}");
    }

    // Hostel
    public function hostel()
    {
        $student = Auth::user()->student;
        $currentSession = AcademicSession::where('is_current', true)->first();

        $allocation = HostelAllocation::where('student_id', $student->id)
            ->where('academic_session_id', $currentSession->id)
            ->with(['hostelRoom', 'hostelRoom.hostelBlock'])
            ->first();

        return view('student.hostel', compact('allocation', 'currentSession'));
    }

    // Apply Hostel
    public function applyHostel(Request $request)
    {
        $student = Auth::user()->student;
        $currentSession = AcademicSession::where('is_current', true)->first();

        // Check if already allocated
        $exists = HostelAllocation::where('student_id', $student->id)
            ->where('academic_session_id', $currentSession->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You are already allocated a hostel room.');
        }

        // Create application
        HostelAllocation::create([
            'student_id' => $student->id,
            'academic_session_id' => $currentSession->id,
            'allocation_date' => now(),
            'status' => 'allocated',
        ]);

        return back()->with('success', 'Hostel application submitted successfully.');
    }

    // ID Card
    public function idCard()
    {
        $student = Auth::user()->student;
        $requests = IdCardRequest::where('student_id', $student->id)
            ->orderBy('requested_at', 'desc')
            ->paginate(10);

        return view('student.id-card', compact('requests'));
    }

    // Request ID Card
    public function requestIdCard(Request $request)
    {
        $student = Auth::user()->student;

        // Check if request already exists
        $pendingRequest = IdCardRequest::where('student_id', $student->id)
            ->where('status', '!=', 'collected')
            ->exists();

        if ($pendingRequest) {
            return back()->with('error', 'You already have a pending ID card request.');
        }

        IdCardRequest::create([
            'student_id' => $student->id,
            'request_number' => 'IDC-' . time() . '-' . $student->id,
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        return back()->with('success', 'ID card request submitted successfully.');
    }

    // Notifications
    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('sent_at', 'desc')
            ->paginate(20);

        return view('student.notifications', compact('notifications'));
    }

    // Mark Notification as Read
    public function markNotificationRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->update([
            'status' => 'read',
            'read_at' => now(),
        ]);

        return back();
    }
}
