<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('credit_units')->default(3);
            $table->integer('level')->default(100); // 100, 200, 300, 400, 500
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->enum('course_type', ['core', 'elective', 'general_studies'])->default('core');
            $table->foreignId('lecturer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('department_id');
            $table->index('level');
        });

        Schema::create('course_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('prerequisite_course_id')->constrained('courses')->onDelete('cascade');
            $table->decimal('minimum_grade', 3, 1)->nullable();
            $table->timestamps();
            
            $table->unique(['course_id', 'prerequisite_course_id']);
        });

        Schema::create('course_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->integer('semester')->default(1); // 1 or 2
            $table->integer('year_level')->default(1);
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
            
            $table->unique(['course_id', 'program_id', 'year_level', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_program');
        Schema::dropIfExists('course_prerequisites');
        Schema::dropIfExists('courses');
    }
};
