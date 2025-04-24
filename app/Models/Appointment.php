<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'scheduled_at',
        'status',
        'notes',
        'fees',
        'is_paid',
        'is_important'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'fees' => 'decimal:2',
        'is_paid' => 'boolean',
        'is_important' => 'boolean'
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'قيد الانتظار',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => 'غير معروف'
        };
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->scheduled_at->isFuture() && $this->status === 'scheduled';
    }

    public function getIsTodayAttribute(): bool
    {
        return $this->scheduled_at->isToday();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now())
                    ->where('status', 'scheduled')
                    ->orderBy('scheduled_at');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', Carbon::today())
                    ->orderBy('scheduled_at');
    }
}