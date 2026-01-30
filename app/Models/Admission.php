<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'application_number',
        'status',
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'program_id',
        'admission_type',
        'jamb_registration_number',
        'jamb_score',
        'post_utme_score',
        'o_level_result_number',
        'o_level_year',
        'additional_qualifications',
        'submitted_at',
        'shortlisted_at',
        'interview_date',
        'interview_remarks',
        'admission_remarks',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'submitted_at' => 'datetime',
        'shortlisted_at' => 'datetime',
        'interview_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AdmissionDocument::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(AdmissionFee::class);
    }

    public function isApproved(): bool
    {
        return in_array($this->status, ['admitted', 'accepted']);
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'submitted']);
    }
}
