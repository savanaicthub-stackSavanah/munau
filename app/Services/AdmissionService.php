<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\AdmissionDocument;
use App\Models\AdmissionFee;
use App\Models\User;
use App\Models\Student;
use App\Models\AcademicSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdmissionService
{
    public function createApplication(array $data): Admission
    {
        return DB::transaction(function () use ($data) {
            $applicationNumber = 'APP-' . date('Y') . '-' . Str::random(8);

            $admission = Admission::create([
                'user_id' => $data['user_id'] ?? null,
                'application_number' => $applicationNumber,
                'status' => 'draft',
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'country' => $data['country'] ?? 'Nigeria',
                'zip_code' => $data['zip_code'] ?? null,
                'program_id' => $data['program_id'],
                'admission_type' => $data['admission_type'] ?? null,
                'jamb_registration_number' => $data['jamb_registration_number'] ?? null,
                'jamb_score' => $data['jamb_score'] ?? null,
                'post_utme_score' => $data['post_utme_score'] ?? null,
                'o_level_result_number' => $data['o_level_result_number'] ?? null,
                'o_level_year' => $data['o_level_year'] ?? null,
                'additional_qualifications' => $data['additional_qualifications'] ?? null,
            ]);

            // Create admission fee record
            AdmissionFee::create([
                'admission_id' => $admission->id,
                'amount' => config('college.admission_fee', 5000),
                'payment_status' => 'pending',
                'due_date' => now()->addDays(7),
            ]);

            return $admission;
        });
    }

    public function submitApplication(Admission $admission): void
    {
        DB::transaction(function () use ($admission) {
            $admission->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            NotificationService::notifyAdmissionSubmitted($admission);
        });
    }

    public function uploadDocument(Admission $admission, array $file, string $documentType): AdmissionDocument
    {
        $path = $file['file']->store("admissions/{$admission->id}/{$documentType}", 'public');

        return AdmissionDocument::updateOrCreate(
            [
                'admission_id' => $admission->id,
                'document_type' => $documentType,
            ],
            [
                'file_path' => $path,
                'original_filename' => $file['file']->getClientOriginalName(),
                'file_size' => $file['file']->getSize(),
                'mime_type' => $file['file']->getMimeType(),
                'uploaded_at' => now(),
            ]
        );
    }

    public function screenApplication(Admission $admission, bool $isShortlisted, string $remarks = ''): void
    {
        DB::transaction(function () use ($admission, $isShortlisted, $remarks) {
            $status = $isShortlisted ? 'shortlisted' : 'rejected';
            
            $admission->update([
                'status' => $status,
                'shortlisted_at' => $isShortlisted ? now() : null,
                'admission_remarks' => $remarks,
            ]);

            if ($isShortlisted) {
                NotificationService::notifyAdmissionShortlisted($admission);
            } else {
                NotificationService::notifyAdmissionRejected($admission);
            }
        });
    }

    public function admitStudent(Admission $admission, string $remarks = ''): void
    {
        DB::transaction(function () use ($admission, $remarks) {
            $admission->update([
                'status' => 'admitted',
                'admission_remarks' => $remarks,
            ]);

            NotificationService::notifyAdmissionApproved($admission);
        });
    }

    public function generateAdmissionLetter(Admission $admission): string
    {
        // Generate PDF admission letter
        $data = [
            'admission' => $admission,
            'program' => $admission->program,
            'date' => now()->format('d F Y'),
        ];

        // This would typically use a PDF library like TCPDF or Dompdf
        // return \PDF::loadView('admission-letter', $data)->stream();
        return "admission_letter_{$admission->id}.pdf";
    }

    public function acceptAdmission(Admission $admission): void
    {
        DB::transaction(function () use ($admission) {
            // Create user account for student
            $user = User::create([
                'role' => 'student',
                'email' => $admission->email,
                'phone' => $admission->phone,
                'password' => bcrypt(Str::random(16)), // Temporary password
                'first_name' => $admission->first_name,
                'last_name' => $admission->last_name,
                'middle_name' => $admission->middle_name,
                'gender' => $admission->gender,
                'date_of_birth' => $admission->date_of_birth,
                'address' => $admission->address,
                'city' => $admission->city,
                'state' => $admission->state,
                'country' => $admission->country,
                'zip_code' => $admission->zip_code,
                'is_active' => true,
            ]);

            // Create student record
            $currentSession = AcademicSession::where('is_current', true)->first();
            
            Student::create([
                'user_id' => $user->id,
                'matric_number' => $this->generateMatricNumber($admission->program_id),
                'registration_number' => 'REG-' . date('Y') . '-' . Str::random(6),
                'status' => 'active',
                'admission_date' => now()->toDateString(),
                'department_id' => $admission->program->department_id,
                'program_id' => $admission->program_id,
                'current_level' => 100,
                'admission_type' => $admission->admission_type,
                'jamb_registration_number' => $admission->jamb_registration_number,
                'is_active' => true,
            ]);

            // Update admission status
            $admission->update([
                'status' => 'accepted',
                'user_id' => $user->id,
            ]);

            NotificationService::notifyStudentOnboarded($user);
        });
    }

    private function generateMatricNumber(int $programId): string
    {
        $program = \App\Models\Program::find($programId);
        $count = Student::where('program_id', $programId)->count() + 1;
        return $program->code . '/' . date('Y') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
