<?php

namespace App\Services;

use App\Models\User;
use App\Models\Admission;
use App\Models\Student;
use App\Models\Notification;
use App\Models\FeePayment;

class NotificationService
{
    public static function notifyAdmissionSubmitted(Admission $admission): void
    {
        Notification::create([
            'user_id' => $admission->user_id,
            'type' => 'admission_submitted',
            'title' => 'Admission Application Submitted',
            'message' => "Your admission application #{$admission->application_number} has been submitted successfully. You will be notified once it's reviewed.",
            'category' => 'admission',
            'action_url' => "/admission/{$admission->id}",
            'sent_at' => now(),
        ]);

        // Also notify admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'new_application',
                'title' => 'New Admission Application',
                'message' => "{$admission->first_name} {$admission->last_name} has submitted an application for {$admission->program->name}",
                'category' => 'admission',
                'action_url' => "/admin/admissions/{$admission->id}",
                'sent_at' => now(),
            ]);
        }
    }

    public static function notifyAdmissionShortlisted(Admission $admission): void
    {
        if ($admission->user_id) {
            Notification::create([
                'user_id' => $admission->user_id,
                'type' => 'admission_shortlisted',
                'title' => 'Shortlisted for Admission',
                'message' => 'Congratulations! You have been shortlisted for admission to ' . $admission->program->name,
                'category' => 'admission',
                'action_url' => "/admission/{$admission->id}",
                'sent_at' => now(),
            ]);
        }
    }

    public static function notifyAdmissionApproved(Admission $admission): void
    {
        if ($admission->user_id) {
            Notification::create([
                'user_id' => $admission->user_id,
                'type' => 'admission_approved',
                'title' => 'Admission Approved',
                'message' => 'Congratulations! Your admission has been approved. Please proceed with the acceptance fee payment.',
                'category' => 'admission',
                'action_url' => "/admission/{$admission->id}",
                'sent_at' => now(),
            ]);
        }
    }

    public static function notifyAdmissionRejected(Admission $admission): void
    {
        if ($admission->user_id) {
            Notification::create([
                'user_id' => $admission->user_id,
                'type' => 'admission_rejected',
                'title' => 'Application Status Update',
                'message' => 'We regret to inform you that your application was not successful at this time. You may reapply in the next admission cycle.',
                'category' => 'admission',
                'action_url' => "/admission/{$admission->id}",
                'sent_at' => now(),
            ]);
        }
    }

    public static function notifyStudentOnboarded(User $user): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'student_onboarded',
            'title' => 'Welcome to Munau College',
            'message' => 'Welcome! Your student account has been created. Please log in and complete your profile.',
            'category' => 'academic',
            'action_url' => '/student/profile',
            'sent_at' => now(),
        ]);
    }

    public static function notifyStudentPayment(Student $student, FeePayment $payment): void
    {
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'payment_received',
            'title' => 'Payment Received',
            'message' => "We have received your payment of {$payment->amount_paid}. Reference: {$payment->payment_reference}",
            'category' => 'finance',
            'action_url' => '/student/finances',
            'sent_at' => now(),
        ]);
    }

    public static function notifyCourseRegistration(Student $student, int $courseCount): void
    {
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'course_registration',
            'title' => 'Course Registration Completed',
            'message' => "You have successfully registered for {$courseCount} courses for this session.",
            'category' => 'academic',
            'action_url' => '/student/courses',
            'sent_at' => now(),
        ]);
    }

    public static function notifyResultsPosted(Student $student): void
    {
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'results_posted',
            'title' => 'Results Posted',
            'message' => 'Your examination results have been posted. Please log in to view your transcript.',
            'category' => 'academic',
            'action_url' => '/student/results',
            'sent_at' => now(),
        ]);
    }

    public static function notifyExaminationSchedule(Student $student): void
    {
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'exam_schedule',
            'title' => 'Examination Schedule Released',
            'message' => 'Your examination schedule has been released. Please check your portal for details.',
            'category' => 'academic',
            'action_url' => '/student/exam-schedule',
            'sent_at' => now(),
        ]);
    }

    public static function notifyHostelAllocation(Student $student, string $roomNumber): void
    {
        Notification::create([
            'user_id' => $student->user_id,
            'type' => 'hostel_allocated',
            'title' => 'Hostel Allocation',
            'message' => "You have been allocated to room {$roomNumber}. Please proceed with check-in.",
            'category' => 'hostel',
            'action_url' => '/student/hostel',
            'sent_at' => now(),
        ]);
    }

    public static function notify(User $user, string $type, string $title, string $message, string $category, string $actionUrl = null): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'category' => $category,
            'action_url' => $actionUrl,
            'sent_at' => now(),
        ]);
    }
}
