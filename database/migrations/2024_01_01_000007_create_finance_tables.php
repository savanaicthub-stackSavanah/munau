<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->decimal('tuition_fee', 12, 2);
            $table->decimal('acceptance_fee', 12, 2)->default(0);
            $table->decimal('registration_fee', 12, 2)->default(0);
            $table->decimal('facilities_fee', 12, 2)->default(0);
            $table->decimal('technology_fee', 12, 2)->default(0);
            $table->decimal('other_charges', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('balance', 12, 2);
            $table->date('due_date');
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->timestamp('first_payment_date')->nullable();
            $table->timestamp('last_payment_date')->nullable();
            $table->timestamp('paid_in_full_date')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'academic_session_id']);
            $table->index('student_id');
            $table->index('payment_status');
        });

        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_fee_id')->constrained('school_fees')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->decimal('amount_paid', 12, 2);
            $table->enum('payment_method', ['bank_transfer', 'card', 'paystack', 'stripe', 'cash', 'cheque'])->nullable();
            $table->string('payment_reference')->unique();
            $table->string('transaction_id')->nullable()->unique();
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            $table->text('payment_remarks')->nullable();
            $table->timestamp('payment_date');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('payment_status');
            $table->index('payment_date');
        });

        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_payment_id')->constrained('fee_payments')->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method');
            $table->text('receipt_details');
            $table->string('receipt_pdf_path')->nullable();
            $table->timestamp('generated_at');
            $table->boolean('is_printed')->default(false);
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('receipt_number');
        });

        Schema::create('fee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->integer('level');
            $table->decimal('tuition_fee', 12, 2);
            $table->decimal('acceptance_fee', 12, 2)->default(0);
            $table->decimal('registration_fee', 12, 2)->default(0);
            $table->decimal('facilities_fee', 12, 2)->default(0);
            $table->decimal('technology_fee', 12, 2)->default(0);
            $table->decimal('other_charges', 12, 2)->default(0);
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['program_id', 'level', 'academic_session_id']);
        });

        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Full Payment, 2 Installments, etc
            $table->text('description')->nullable();
            $table->integer('number_of_installments')->default(1);
            $table->decimal('first_installment_percentage', 5, 2)->default(100);
            $table->decimal('subsequent_installment_percentage', 5, 2)->nullable();
            $table->integer('days_between_installments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_plans');
        Schema::dropIfExists('fee_schedules');
        Schema::dropIfExists('payment_receipts');
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('school_fees');
    }
};
