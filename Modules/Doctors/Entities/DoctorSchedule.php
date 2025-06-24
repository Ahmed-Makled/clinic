<?php

namespace Modules\Doctors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTime;
use DateInterval;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day',
        'start_time',
        'end_time',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the doctor that owns the schedule.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getAvailableSlots($date)
    {
        // Get day name in lowercase English
        $dayName = strtolower($date->format('l'));

        if (!$this->is_active || $this->day !== $dayName) {
            return [];
        }

        $slots = [];
        $startTime = new DateTime($date->format('Y-m-d ') . $this->start_time->format('H:i'));
        $endTime = new DateTime($date->format('Y-m-d ') . $this->end_time->format('H:i'));
          // Use doctor's waiting_time dynamically, fallback to 30 minutes if not set
        $waitingTimeMinutes = $this->doctor->waiting_time ?? 30;
        $interval = new DateInterval('PT' . $waitingTimeMinutes . 'M');

        while ($startTime < $endTime) {
            $slots[] = $startTime->format('H:i');
            $startTime->add($interval);
        }

        return $slots;
    }
}
