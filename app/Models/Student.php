<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'matric_number',
        'registration_number',
        'status',
        'admission_date',
        'department_id',
        'program_id',
        'current_level',
        'admission_type',
        'jamb_registration_number',
        'cgpa',
        'blood_group',
        'national_id',
        'next_of_kin_name',
        'next_of_kin_phone',
        'next_of_kin_relationship',
        'is_sponsored',
        'sponsor_name',
        'sponsor_contact',
        'sponsor_occupation',
        'is_active',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'is_sponsored' => 'boolean',
        'is_active' => 'boolean',
        'cgpa' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function courseEnrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function schoolFees(): HasMany
    {
        return $this->hasMany(SchoolFee::class);
    }

    public function hostelAllocations(): HasMany
    {
        return $this->hasMany(HostelAllocation::class);
    }

    public function idCardRequests(): HasMany
    {
        return $this->hasMany(IdCardRequest::class);
    }
}
