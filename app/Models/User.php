<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role',
        'email',
        'phone',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'date_of_birth',
        'profile_photo',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'is_active',
        'is_verified',
        'verification_token',
        'email_verified_at',
        'last_login',
        'two_factor_token',
        'two_factor_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
        'two_factor_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'password' => 'hashed',
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }
}
