<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope for specific user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Get user-friendly action name
     */
    public function getActionLabel()
    {
        $actions = [
            'login' => 'User Login',
            'logout' => 'User Logout',
            'password_change' => 'Password Changed',
            'profile_update' => 'Profile Updated',
            'admission_application' => 'Admission Application',
            'admission_approved' => 'Admission Approved',
            'admission_rejected' => 'Admission Rejected',
            'course_registration' => 'Course Registration',
            'payment_initiated' => 'Payment Initiated',
            'payment_completed' => 'Payment Completed',
            'payment_failed' => 'Payment Failed',
            'payment_verified' => 'Payment Verified',
            'document_uploaded' => 'Document Uploaded',
            'id_card_requested' => 'ID Card Requested',
            'hostel_applied' => 'Hostel Application',
            'hostel_allocated' => 'Hostel Allocated',
        ];

        return $actions[$this->action] ?? ucfirst(str_replace('_', ' ', $this->action));
    }
}
