<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorRating extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'rating',
        'comment',
        'is_verified'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_verified' => 'boolean'
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
