<?php

namespace App\Models;

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
        'slot_duration',
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
        if (!$this->is_active || strtolower($date->format('l')) !== $this->day) {
            return [];
        }

        $slots = [];
        $startTime = new DateTime($date->format('Y-m-d ') . $this->start_time->format('H:i'));
        $endTime = new DateTime($date->format('Y-m-d ') . $this->end_time->format('H:i'));
        $interval = new DateInterval('PT' . $this->slot_duration . 'M');

        while ($startTime < $endTime) {
            $slots[] = $startTime->format('H:i');
            $startTime->add($interval);
        }

        return $slots;
    }
}
