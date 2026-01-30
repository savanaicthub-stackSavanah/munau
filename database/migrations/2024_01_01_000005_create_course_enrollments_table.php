<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->integer('semester')->default(1);
            $table->enum('status', ['active', 'completed', 'dropped', 'incomplete'])->default('active');
            $table->decimal('score', 5, 2)->nullable();
            $table->string('grade')->nullable();
            $table->decimal('grade_point', 3, 1)->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->timestamp('enrollment_date');
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['student_id', 'course_id', 'academic_session_id']);
            $table->index('student_id');
            $table->index('status');
        });

        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_name')->unique(); // 2024/2025
            $table->integer('start_year');
            $table->integer('end_year');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->date('registration_opens');
            $table->date('registration_closes');
            $table->date('semester1_starts');
            $table->date('semester1_ends');
            $table->date('semester2_starts');
            $table->date('semester2_ends');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
            
            $table->index('start_year');
        });

        Schema::create('examination_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->integer('semester');
            $table->dateTime('exam_date_time');
            $table->integer('duration_minutes')->default(120);
            $table->string('venue')->nullable();
            $table->integer('max_candidates')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
            
            $table->unique(['course_id', 'academic_session_id', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examination_schedules');
        Schema::dropIfExists('course_enrollments');
        Schema::dropIfExists('academic_sessions');
    }
};
