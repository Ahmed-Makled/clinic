<?php

namespace Modules\Payments\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointments\Entities\Appointment;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'appointment_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_id',
        'transaction_id'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2'
    ];

    /**
     * Get the appointment associated with the payment.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Determine if the payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Determine if the payment has failed.
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Generate a unique transaction ID.
     */
    public static function generateTransactionId(): string
    {
        return 'PAY-' . strtoupper(substr(md5(uniqid()), 0, 10)) . '-' . time();
    }
}
