<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicSession extends Model
{
    use HasFactory;

    protected $table = 'academic_sessions';

    protected $fillable = [
        'session_name',
        'start_year',
        'end_year',
        'status',
        'registration_opens',
        'registration_closes',
        'semester1_starts',
        'semester1_ends',
        'semester2_starts',
        'semester2_ends',
        'is_current',
    ];

    protected $casts = [
        'registration_opens' => 'date',
        'registration_closes' => 'date',
        'semester1_starts' => 'date',
        'semester1_ends' => 'date',
        'semester2_starts' => 'date',
        'semester2_ends' => 'date',
        'is_current' => 'boolean',
    ];

    public function courseEnrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function schoolFees(): HasMany
    {
        return $this->hasMany(SchoolFee::class);
    }

    public function examSchedules(): HasMany
    {
        return $this->hasMany(ExaminationSchedule::class);
    }

    public static function current()
    {
        return self::where('is_current', true)->first();
    }

    public function isRegistrationOpen(): bool
    {
        return now()->isBetween($this->registration_opens, $this->registration_closes);
    }
}
