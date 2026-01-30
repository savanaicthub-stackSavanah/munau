<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('matric_number')->unique();
            $table->string('registration_number')->unique();
            $table->enum('status', ['active', 'graduated', 'suspended', 'withdrawn'])->default('active');
            $table->date('admission_date');
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->foreignId('program_id')->constrained('programs')->onDelete('restrict');
            $table->integer('current_level')->default(100); // 100, 200, 300, 400, 500
            $table->enum('admission_type', ['utme', 'post_utme', 'merit', 'direct_entry'])->nullable();
            $table->string('jamb_registration_number')->nullable();
            $table->decimal('cgpa', 4, 2)->default(0.00);
            $table->string('blood_group')->nullable();
            $table->string('national_id')->nullable();
            $table->string('next_of_kin_name')->nullable();
            $table->string('next_of_kin_phone')->nullable();
            $table->string('next_of_kin_relationship')->nullable();
            $table->boolean('is_sponsored')->default(false);
            $table->string('sponsor_name')->nullable();
            $table->string('sponsor_contact')->nullable();
            $table->string('sponsor_occupation')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('matric_number');
            $table->index('user_id');
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
