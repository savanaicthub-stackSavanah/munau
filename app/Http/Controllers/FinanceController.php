<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolFee;
use App\Models\Payment;
use App\Models\Receipt;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
        $this->middleware('auth');
        $this->middleware('student');
    }

    /**
     * Display student fee payment status
     */
    public function index()
    {
        $student = auth()->user()->student;
        
        $totalCharges = $this->financeService->calculateTotalCharges($student);
        $amountPaid = $this->financeService->getTotalPaid($student);
        $outstandingBalance = $totalCharges - $amountPaid;
        
        $feeBreakdown = $this->financeService->getFeeBreakdown($student);
        $paymentHistory = Payment::where('student_id', $student->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $academicSession = $student->academicSession;

        return view('student.fees', [
            'totalCharges' => $totalCharges,
            'amountPaid' => $amountPaid,
            'outstandingBalance' => $outstandingBalance,
            'feeBreakdown' => $feeBreakdown,
            'paymentHistory' => $paymentHistory,
            'academicSession' => $academicSession,
        ]);
    }

    /**
     * Process student payment
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:bank_transfer,card,paystack,flutterwave',
        ]);

        $student = auth()->user()->student;
        $outstandingBalance = $this->financeService->getOutstandingBalance($student);

        if ($request->amount > $outstandingBalance) {
            return back()->with('error', 'Payment amount exceeds outstanding balance');
        }

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'student_id' => $student->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_number' => $this->generateReferenceNumber(),
                'description' => 'School Fees Payment',
                'status' => 'pending',
                'payment_date' => Carbon::now(),
            ]);

            // Process payment based on method
            switch ($request->payment_method) {
                case 'paystack':
                    // Initialize Paystack payment
                    return $this->initializePaystack($payment);
                case 'flutterwave':
                    // Initialize Flutterwave payment
                    return $this->initializeFlutterwave($payment);
                case 'card':
                    // Redirect to payment gateway
                    return $this->initializeCardPayment($payment);
                default:
                    // Bank transfer - manual verification
                    return view('student.bank-transfer', ['payment' => $payment]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Verify and complete payment
     */
    public function verifyPayment(Request $request, $paymentId)
    {
        $payment = Payment::find($paymentId);
        
        if (!$payment) {
            return back()->with('error', 'Payment not found');
        }

        DB::beginTransaction();
        try {
            // Verify with payment gateway
            $verified = $this->verifyWithGateway($payment);
            
            if ($verified) {
                $payment->update(['status' => 'completed']);
                
                // Create receipt
                $receipt = Receipt::create([
                    'payment_id' => $payment->id,
                    'student_id' => $payment->student_id,
                    'receipt_number' => $this->generateReceiptNumber(),
                    'amount' => $payment->amount,
                    'issued_date' => Carbon::now(),
                ]);

                // Update fee records
                $this->financeService->recordPayment($payment);

                DB::commit();
                
                return redirect()->route('student.fees')
                    ->with('success', 'Payment completed successfully. Receipt #' . $receipt->receipt_number);
            } else {
                $payment->update(['status' => 'failed']);
                DB::commit();
                
                return back()->with('error', 'Payment verification failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment verification error: ' . $e->getMessage());
        }
    }

    /**
     * Download receipt
     */
    public function downloadReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        // Verify ownership
        if ($payment->student_id !== auth()->user()->student->id) {
            abort(403);
        }

        $receipt = $payment->receipt;
        
        // Generate PDF receipt (using Laravel-DOMPDF or similar)
        return view('finance.receipt-pdf', ['receipt' => $receipt, 'payment' => $payment]);
    }

    /**
     * Get admission fee status
     */
    public function admissionFeeStatus()
    {
        $student = auth()->user()->student;
        
        if (!$student->admission) {
            return redirect()->route('student.dashboard');
        }

        $admissionFee = $student->admission->admissionFee;
        
        return view('finance.admission-fee', [
            'admissionFee' => $admissionFee,
            'student' => $student,
        ]);
    }

    /**
     * Pay admission fee
     */
    public function payAdmissionFee(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:bank_transfer,card,paystack,flutterwave',
        ]);

        $student = auth()->user()->student;
        
        if (!$student->admission) {
            return back()->with('error', 'No admission record found');
        }

        // Similar to processPayment but for admission fees
        return $this->processPayment($request);
    }

    /**
     * Generate unique reference number
     */
    private function generateReferenceNumber()
    {
        return 'MCHS-' . strtoupper(uniqid()) . '-' . Carbon::now()->format('YmdHis');
    }

    /**
     * Generate receipt number
     */
    private function generateReceiptNumber()
    {
        return 'RCP-' . Carbon::now()->format('Y') . '-' . sprintf('%06d', Receipt::count() + 1);
    }

    /**
     * Initialize Paystack payment
     */
    private function initializePaystack($payment)
    {
        // Implement Paystack integration
        return redirect()->away('https://checkout.paystack.com/...');
    }

    /**
     * Initialize Flutterwave payment
     */
    private function initializeFlutterwave($payment)
    {
        // Implement Flutterwave integration
        return redirect()->away('https://checkout.flutterwave.com/...');
    }

    /**
     * Initialize card payment
     */
    private function initializeCardPayment($payment)
    {
        // Implement card payment gateway
        return view('finance.card-payment', ['payment' => $payment]);
    }

    /**
     * Verify payment with gateway
     */
    private function verifyWithGateway($payment)
    {
        // Implement gateway verification
        return true;
    }
}
