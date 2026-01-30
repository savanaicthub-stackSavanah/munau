<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('application_number')->unique();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'shortlisted', 'interviewed', 'admitted', 'rejected', 'deferred', 'accepted'])->default('draft');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Nigeria');
            $table->string('zip_code')->nullable();
            $table->foreignId('program_id')->constrained('programs')->onDelete('restrict');
            $table->enum('admission_type', ['utme', 'post_utme', 'merit', 'direct_entry'])->nullable();
            $table->string('jamb_registration_number')->nullable();
            $table->integer('jamb_score')->nullable();
            $table->decimal('post_utme_score', 5, 2)->nullable();
            $table->string('o_level_result_number')->nullable();
            $table->integer('o_level_year')->nullable();
            $table->text('additional_qualifications')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('interview_date')->nullable();
            $table->text('interview_remarks')->nullable();
            $table->text('admission_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('application_number');
            $table->index('status');
            $table->index('program_id');
            $table->index('email');
        });

        Schema::create('admission_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained('admissions')->onDelete('cascade');
            $table->string('document_type'); // birth_cert, o_level, transcript, passport, etc
            $table->string('file_path');
            $table->string('original_filename');
            $table->integer('file_size');
            $table->string('mime_type');
            $table->timestamp('uploaded_at');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('verification_remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['admission_id', 'document_type']);
            $table->index('admission_id');
        });

        Schema::create('admission_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->constrained('admissions')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_reference')->nullable()->unique();
            $table->string('payment_method')->nullable(); // card, transfer, paystack, stripe
            $table->text('payment_response')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('due_date');
            $table->timestamps();
            
            $table->index('admission_id');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_fees');
        Schema::dropIfExists('admission_documents');
        Schema::dropIfExists('admissions');
    }
};
