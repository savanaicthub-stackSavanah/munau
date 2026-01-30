<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('total_rooms');
            $table->enum('block_type', ['male', 'female', 'mixed'])->default('mixed');
            $table->string('warden_name')->nullable();
            $table->string('warden_phone')->nullable();
            $table->string('warden_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_block_id')->constrained('hostel_blocks')->onDelete('cascade');
            $table->string('room_number')->unique();
            $table->integer('capacity');
            $table->integer('current_occupants')->default(0);
            $table->enum('status', ['available', 'full', 'maintenance', 'closed'])->default('available');
            $table->decimal('accommodation_fee_per_semester', 12, 2);
            $table->timestamps();
            
            $table->index('hostel_block_id');
        });

        Schema::create('hostel_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('hostel_room_id')->constrained('hostel_rooms')->onDelete('cascade');
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->date('allocation_date');
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->enum('status', ['allocated', 'checked_in', 'checked_out', 'cancelled'])->default('allocated');
            $table->text('allocation_remarks')->nullable();
            $table->boolean('damage_deposit_paid')->default(false);
            $table->timestamp('damage_deposit_refunded_at')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'academic_session_id']);
            $table->index('student_id');
            $table->index('status');
        });

        Schema::create('hostel_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('hostel_allocation_id')->nullable()->constrained('hostel_allocations')->onDelete('set null');
            $table->string('complaint_type');
            $table->text('complaint_description');
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->enum('status', ['open', 'acknowledged', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('status');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->enum('status', ['unread', 'read', 'archived'])->default('unread');
            $table->string('category')->nullable(); // admission, finance, academic, hostel, etc
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('sent_at');
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->string('model');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('status')->default('success');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('action');
            $table->index('model');
            $table->index('created_at');
        });

        Schema::create('id_card_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('request_number')->unique();
            $table->enum('status', ['requested', 'approved', 'printing', 'ready_for_pickup', 'collected', 'cancelled'])->default('requested');
            $table->timestamp('requested_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->string('collection_location')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('student_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('id_card_requests');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('hostel_complaints');
        Schema::dropIfExists('hostel_allocations');
        Schema::dropIfExists('hostel_rooms');
        Schema::dropIfExists('hostel_blocks');
    }
};
