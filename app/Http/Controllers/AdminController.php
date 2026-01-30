<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Admission;
use App\Models\Payment;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_pending_admissions' => Admission::where('status', 'pending')->count(),
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'recent_admissions' => Admission::latest()->limit(5)->get(),
            'recent_payments' => Payment::where('status', 'completed')->latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', $stats);
    }

    /**
     * Manage admissions
     */
    public function admissions(Request $request)
    {
        $query = Admission::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('program') && $request->program !== '') {
            $query->where('program_id', $request->program);
        }

        if ($request->has('search')) {
            $query->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        $admissions = $query->paginate(15);
        $programs = \App\Models\Program::all();

        return view('admin.admissions.index', [
            'admissions' => $admissions,
            'programs' => $programs,
        ]);
    }

    /**
     * View admission details
     */
    public function admissionDetail($id)
    {
        $admission = Admission::findOrFail($id);
        $documents = $admission->documents;

        return view('admin.admissions.detail', [
            'admission' => $admission,
            'documents' => $documents,
        ]);
    }

    /**
     * Approve admission
     */
    public function approveAdmission(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $admission->update([
                'status' => 'approved',
                'approved_at' => Carbon::now(),
                'approved_by' => auth()->id(),
            ]);

            // Generate admission letter
            $this->generateAdmissionLetter($admission);

            // Log action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'admission_approved',
                'target_id' => $admission->id,
                'target_type' => 'Admission',
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);

            DB::commit();

            return back()->with('success', 'Admission approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error approving admission: ' . $e->getMessage());
        }
    }

    /**
     * Reject admission
     */
    public function rejectAdmission(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);

        $admission = Admission::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $admission->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
                'rejected_at' => Carbon::now(),
                'rejected_by' => auth()->id(),
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'admission_rejected',
                'target_id' => $admission->id,
                'target_type' => 'Admission',
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);

            DB::commit();

            return back()->with('success', 'Admission rejected');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error rejecting admission: ' . $e->getMessage());
        }
    }

    /**
     * Manage students
     */
    public function students(Request $request)
    {
        $query = Student::query();

        if ($request->has('program') && $request->program !== '') {
            $query->where('program_id', $request->program);
        }

        if ($request->has('level') && $request->level !== '') {
            $query->where('current_level', $request->level);
        }

        if ($request->has('search')) {
            $query->where('matric_number', 'like', '%' . $request->search . '%')
                  ->orWhere('first_name', 'like', '%' . $request->search . '%');
        }

        $students = $query->paginate(20);
        $programs = \App\Models\Program::all();

        return view('admin.students.index', [
            'students' => $students,
            'programs' => $programs,
        ]);
    }

    /**
     * View student profile
     */
    public function studentDetail($id)
    {
        $student = Student::findOrFail($id);
        $enrollments = $student->enrollments;
        $payments = $student->payments;

        return view('admin.students.detail', [
            'student' => $student,
            'enrollments' => $enrollments,
            'payments' => $payments,
        ]);
    }

    /**
     * Manage payments
     */
    public function payments(Request $request)
    {
        $query = Payment::query();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('method') && $request->method !== '') {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->with('student')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payments.index', ['payments' => $payments]);
    }

    /**
     * Verify payment
     */
    public function verifyPayment($id)
    {
        $payment = Payment::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $payment->update(['status' => 'completed', 'verified_at' => Carbon::now()]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'payment_verified',
                'target_id' => $payment->id,
                'target_type' => 'Payment',
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);

            DB::commit();

            return back()->with('success', 'Payment verified');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

    /**
     * View audit logs
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::query();

        if ($request->has('action') && $request->action !== '') {
            $query->where('action', $request->action);
        }

        if ($request->has('user') && $request->user !== '') {
            $query->where('user_id', $request->user);
        }

        $logs = $query->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.audit-logs', ['logs' => $logs]);
    }

    /**
     * Generate admission letter
     */
    private function generateAdmissionLetter($admission)
    {
        // Implementation for generating admission letter
        // This could generate a PDF or save to storage
        $letter = "Dear {$admission->first_name},\n\n";
        $letter .= "Congratulations! You have been admitted to Munau College...\n";
        
        // Save or generate PDF
        return $letter;
    }
}
