<?php

namespace Modules\Appointments\Entities;

use App\Traits\HasStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Modules\Doctors\Entities\Doctor;
use Modules\Patients\Entities\Patient;
use Modules\Payments\Entities\Payment;

class Appointment extends Model
{
    use HasStatus, Searchable;

    /**
     * The status column for this model.
     */
    const STATUS_COLUMN = 'status';

    /**
     * The valid statuses for this model.
     */
    const STATUSES = [
        'scheduled',
        'completed',
        'cancelled'
    ];

    /**
     * The status labels for human-readable display.
     */
    const STATUS_LABELS = [
        'scheduled' => 'في الانتظار',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي'
    ];

    /**
     * The status colors for UI display.
     */
    const STATUS_COLORS = [
        'scheduled' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'scheduled_at',
        'status',
        'notes',
        'fees',
        'waiting_time',
        'is_important'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'fees' => 'decimal:2',
        'is_important' => 'boolean',
        'waiting_time' => 'integer'
    ];

    /**
     * The attributes that should be appended to serialized outputs.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'status_text',
        'status_color',
        'is_upcoming',
        'is_today',
        'formatted_date',
        'formatted_time',
        'activity_icon_class'
    ];

    /**
     * The attributes that should be searchable.
     *
     * @var array<int, string>
     */
    protected $searchable = [
        'notes',
        'status'
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($appointment) {
            if (!$appointment->fees) {
                $doctor = Doctor::find($appointment->doctor_id);
                $appointment->fees = $doctor->consultation_fee;
            }
        });

        // Update appointment information when needed
        static::updating(function ($appointment) {
            // Any other updating logic can go here
        });
    }

    /**
     * Get the doctor that owns the appointment.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient that owns the appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the payment associated with the appointment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'appointment_id');
    }

    /**
     * Get the color associated with the appointment status.
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return static::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    /**
     * Get the localized text for the appointment status.
     *
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'قيد الانتظار',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => 'غير معروف'
        };
    }

    /**
     * Get the activity icon class based on status.
     *
     * @return string
     */
    public function getActivityIconClassAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Determine if the appointment is upcoming.
     *
     * @return bool
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->scheduled_at->isFuture() && $this->status === 'scheduled';
    }

    /**
     * Determine if the appointment is today.
     *
     * @return bool
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->scheduled_at->isToday();
    }

    /**
     * Get the formatted date for the appointment.
     *
     * @return string
     */
    public function getFormattedDateAttribute(): string
    {
        return format_date($this->scheduled_at, 'Y-m-d');
    }

    /**
     * Get the formatted time for the appointment.
     *
     * @return string
     */
    public function getFormattedTimeAttribute(): string
    {
        return format_time($this->scheduled_at, 'h:i A');
    }

    /**
     * Determine if the appointment has been paid.
     *
     * @return bool
     */
    public function getIsPaidAttribute(): bool
    {
        // Calculate payment status based on the relationship
        if ($this->relationLoaded('payment')) {
            return $this->payment && $this->payment->status === 'completed';
        }

        // Check if there's a completed payment for this appointment
        return $this->payment()->where('status', 'completed')->exists();
    }

    /**
     * Get the payment method for this appointment.
     *
     * @return string|null
     */
    public function getPaymentMethodAttribute(): ?string
    {
        if ($this->relationLoaded('payment') && $this->payment) {
            return $this->payment->payment_method;
        }

        $payment = $this->payment()->first();
        return $payment ? $payment->payment_method : null;
    }

    /**
     * Scope a query to only include upcoming appointments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now())
                    ->where('status', 'scheduled')
                    ->orderBy('scheduled_at');
    }

    /**
     * Scope a query to only include today's appointments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', Carbon::today())
                    ->orderBy('scheduled_at');
    }

    /**
     * Scope a query to only include appointments for a specific doctor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $doctorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDoctor($query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope a query to only include appointments for a specific patient.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $patientId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope a query to only include appointments within a date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenDates($query, string $startDate, string $endDate)
    {
        return $query->whereDate('scheduled_at', '>=', $startDate)
                    ->whereDate('scheduled_at', '<=', $endDate)
                    ->orderBy('scheduled_at');
    }

    /**
     * Scope a query to check for appointment conflicts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $doctorId
     * @param \Carbon\Carbon|string $scheduledAt
     * @param int $durationMinutes
     * @param int|null $excludeAppointmentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasConflict($query, $doctorId, $scheduledAt, $durationMinutes = 30, $excludeAppointmentId = null)
    {
        $startTime = $scheduledAt instanceof Carbon ? $scheduledAt : Carbon::parse($scheduledAt);
        $endTime = (clone $startTime)->addMinutes($durationMinutes);

        $query->where('doctor_id', $doctorId)
            ->where('status', 'scheduled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('scheduled_at', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime) {
                        $q->where('scheduled_at', '<=', $startTime)
                            ->whereRaw("DATE_ADD(scheduled_at, INTERVAL 30 MINUTE) >= ?", [$startTime]);
                    });
            });

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query;
    }
}
