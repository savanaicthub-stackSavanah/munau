<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionDocument;
use App\Models\AdmissionFee;
use App\Models\Program;
use App\Services\AdmissionService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Auth;

class AdmissionController extends Controller
{
    protected $admissionService;

    public function __construct(AdmissionService $admissionService)
    {
        $this->admissionService = $admissionService;
    }

    // Apply for admission (form)
    public function apply()
    {
        $programs = Program::where('is_active', true)->get();
        return view('admission.apply', compact('programs'));
    }

    // Store new application
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'program_id' => 'required|exists:programs,id',
            'admission_type' => 'required|in:utme,post_utme,merit,direct_entry',
            'jamb_registration_number' => 'nullable|string|max:20',
            'jamb_score' => 'nullable|integer|between:0,400',
            'o_level_result_number' => 'nullable|string|max:50',
            'o_level_year' => 'nullable|integer|between:2000,' . date('Y'),
        ]);

        try {
            // Check if user is authenticated
            $userId = Auth::id() ?? null;

            $validated['user_id'] = $userId;
            $validated['country'] = 'Nigeria';

            $admission = $this->admissionService->createApplication($validated);

            return redirect()->route('admission.show', $admission->id)
                ->with('success', 'Application created successfully! Please upload required documents.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create application: ' . $e->getMessage());
        }
    }

    // View application
    public function show($id)
    {
        $admission = Admission::findOrFail($id);

        // Check authorization
        if (Auth::check() && Auth::id() !== $admission->user_id) {
            abort(403);
        }

        $documents = $admission->documents()->get();
        $fees = $admission->fees()->first();

        return view('admission.show', compact('admission', 'documents', 'fees'));
    }

    // Update application
    public function update(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);

        if ($admission->status !== 'draft' && $admission->status !== 'submitted') {
            return back()->with('error', 'This application cannot be edited at this stage.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'program_id' => 'required|exists:programs,id',
            'admission_type' => 'required|in:utme,post_utme,merit,direct_entry',
            'jamb_registration_number' => 'nullable|string|max:20',
            'jamb_score' => 'nullable|integer|between:0,400',
            'o_level_result_number' => 'nullable|string|max:50',
            'o_level_year' => 'nullable|integer|between:2000,' . date('Y'),
        ]);

        $admission->update($validated);

        return back()->with('success', 'Application updated successfully.');
    }

    // Submit application
    public function submit($id)
    {
        $admission = Admission::findOrFail($id);

        if ($admission->status !== 'draft') {
            return back()->with('error', 'This application has already been submitted.');
        }

        // Check if required documents are uploaded
        $requiredDocs = ['birth_certificate', 'o_level_result', 'passport_photo'];
        $uploadedDocs = $admission->documents()->pluck('document_type')->toArray();

        $missingDocs = array_diff($requiredDocs, $uploadedDocs);

        if (!empty($missingDocs)) {
            return back()->with('error', 'Please upload all required documents before submitting.');
        }

        $this->admissionService->submitApplication($admission);

        return back()->with('success', 'Application submitted successfully! You will receive updates via email.');
    }

    // Upload document
    public function uploadDocument(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);

        $validated = $request->validate([
            'document_type' => 'required|string|max:50',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        try {
            $document = $this->admissionService->uploadDocument($admission, $validated, $validated['document_type']);

            return back()->with('success', 'Document uploaded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload document: ' . $e->getMessage());
        }
    }

    // Pay admission fee
    public function payAdmissionFee(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);
        $fee = $admission->fees()->first();

        if (!$fee || $fee->payment_status === 'paid') {
            return back()->with('error', 'Fee payment already completed or not found.');
        }

        // This would integrate with payment gateway (Paystack, Stripe, etc.)
        // For now, create a payment simulation
        $validated = $request->validate([
            'payment_method' => 'required|in:card,transfer,paystack,stripe',
        ]);

        // Redirect to payment gateway
        // return redirect()->to(PaymentGateway::generateLink($fee));

        return view('admission.payment', compact('admission', 'fee'));
    }

    // Track application
    public function tracking(Request $request)
    {
        $applicationNumber = $request->input('application_number');
        $email = $request->input('email');

        if (!$applicationNumber || !$email) {
            return view('admission.tracking');
        }

        $admission = Admission::where('application_number', $applicationNumber)
            ->where('email', $email)
            ->first();

        if (!$admission) {
            return back()->with('error', 'Application not found. Please check your application number and email.');
        }

        return view('admission.tracking', compact('admission'));
    }
}
