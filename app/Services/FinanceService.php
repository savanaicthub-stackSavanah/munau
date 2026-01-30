<?php

namespace App\Services;

use App\Models\Student;
use App\Models\SchoolFee;
use App\Models\FeePayment;
use App\Models\PaymentReceipt;
use App\Models\AcademicSession;
use App\Models\FeeSchedule;
use Illuminate\Support\Str;

class FinanceService
{
    public function generateSchoolFee(Student $student, AcademicSession $session): SchoolFee
    {
        $schedule = FeeSchedule::where('program_id', $student->program_id)
            ->where('level', $student->current_level)
            ->where('academic_session_id', $session->id)
            ->first();

        if (!$schedule) {
            throw new \Exception('Fee schedule not found for this program and level');
        }

        $totalAmount = $schedule->tuition_fee + 
                      $schedule->acceptance_fee + 
                      $schedule->registration_fee + 
                      $schedule->facilities_fee + 
                      $schedule->technology_fee + 
                      $schedule->other_charges;

        return SchoolFee::create([
            'student_id' => $student->id,
            'academic_session_id' => $session->id,
            'tuition_fee' => $schedule->tuition_fee,
            'acceptance_fee' => $schedule->acceptance_fee,
            'registration_fee' => $schedule->registration_fee,
            'facilities_fee' => $schedule->facilities_fee,
            'technology_fee' => $schedule->technology_fee,
            'other_charges' => $schedule->other_charges,
            'total_amount' => $totalAmount,
            'amount_paid' => 0,
            'balance' => $totalAmount,
            'due_date' => $session->registration_closes,
            'payment_status' => 'pending',
        ]);
    }

    public function recordPayment(SchoolFee $schoolFee, array $paymentData): FeePayment
    {
        $payment = FeePayment::create([
            'school_fee_id' => $schoolFee->id,
            'student_id' => $schoolFee->student_id,
            'amount_paid' => $paymentData['amount'],
            'payment_method' => $paymentData['method'],
            'payment_reference' => 'PAY-' . time() . '-' . Str::random(8),
            'transaction_id' => $paymentData['transaction_id'] ?? null,
            'payment_status' => $paymentData['status'] ?? 'completed',
            'payment_remarks' => $paymentData['remarks'] ?? null,
            'payment_date' => now(),
        ]);

        // Update school fee
        $this->updateSchoolFeeBalance($schoolFee);

        // Generate receipt
        $this->generatePaymentReceipt($payment);

        // Create notification
        NotificationService::notifyStudentPayment($schoolFee->student, $payment);

        return $payment;
    }

    public function updateSchoolFeeBalance(SchoolFee $schoolFee): void
    {
        $totalPaid = $schoolFee->payments()->where('payment_status', 'completed')->sum('amount_paid');
        
        $schoolFee->update([
            'amount_paid' => $totalPaid,
            'balance' => $schoolFee->total_amount - $totalPaid,
            'payment_status' => $this->determinePaymentStatus($schoolFee->total_amount, $totalPaid),
            'last_payment_date' => now(),
        ]);

        if ($schoolFee->balance <= 0) {
            $schoolFee->update(['paid_in_full_date' => now()]);
        }
    }

    private function determinePaymentStatus(float $total, float $paid): string
    {
        if ($paid >= $total) {
            return 'paid';
        }
        if ($paid > 0) {
            return 'partial';
        }
        return now()->isAfter(\Carbon\Carbon::parse(SchoolFee::find(1)?->due_date)) ? 'overdue' : 'pending';
    }

    public function generatePaymentReceipt(FeePayment $payment): PaymentReceipt
    {
        return PaymentReceipt::create([
            'fee_payment_id' => $payment->id,
            'student_id' => $payment->student_id,
            'amount' => $payment->amount_paid,
            'payment_method' => $payment->payment_method,
            'receipt_number' => 'REC-' . date('Y') . '-' . Str::random(10),
            'receipt_details' => json_encode([
                'reference' => $payment->payment_reference,
                'transaction_id' => $payment->transaction_id,
                'date' => $payment->payment_date,
                'status' => $payment->payment_status,
            ]),
            'generated_at' => now(),
        ]);
    }

    public function getOutstandingFees(Student $student)
    {
        return SchoolFee::where('student_id', $student->id)
            ->whereIn('payment_status', ['pending', 'partial', 'overdue'])
            ->get();
    }

    public function getPaymentHistory(Student $student, $limit = 10)
    {
        return FeePayment::where('student_id', $student->id)
            ->where('payment_status', 'completed')
            ->orderBy('payment_date', 'desc')
            ->limit($limit)
            ->get();
    }
}
