<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolFee extends Model
{
    use HasFactory;

    protected $table = 'school_fees';

    protected $fillable = [
        'student_id',
        'academic_session_id',
        'tuition_fee',
        'acceptance_fee',
        'registration_fee',
        'facilities_fee',
        'technology_fee',
        'other_charges',
        'total_amount',
        'amount_paid',
        'balance',
        'due_date',
        'payment_status',
        'first_payment_date',
        'last_payment_date',
        'paid_in_full_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'first_payment_date' => 'datetime',
        'last_payment_date' => 'datetime',
        'paid_in_full_date' => 'datetime',
        'tuition_fee' => 'decimal:2',
        'acceptance_fee' => 'decimal:2',
        'registration_fee' => 'decimal:2',
        'facilities_fee' => 'decimal:2',
        'technology_fee' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    public function calculateBalance(): void
    {
        $this->balance = $this->total_amount - $this->amount_paid;
        $this->save();
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->payment_status === 'overdue' || 
               (now()->isAfter($this->due_date) && !$this->isPaid());
    }
}
