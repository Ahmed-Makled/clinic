<?php

namespace Modules\Doctors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Patients\Entities\Patient;
use Modules\Appointments\Entities\Appointment;
use Carbon\Carbon;

class DoctorRating extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_id',
        'rating',
        'comment',
        'is_verified'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_verified' => 'boolean'
    ];

    /**
     * مصفوفة تحتوي على المفاتيح المطلوب أن تكون فريدة
     * هذه المصفوفة تضمن أن كل مريض يمكنه تقييم كل حجز مرة واحدة فقط
     */
    public static $uniqueKeys = [
        'patient_id',
        'appointment_id'
    ];

    /**
     * التأكد من صحة الحجز قبل إنشاء التقييم - يجب أن يكون مكتملاً
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($rating) {
            $appointment = Appointment::find($rating->appointment_id);

            // التحقق من أن الحجز مكتمل
            if (!$appointment || $appointment->status !== 'completed') {
                return false;
            }

            // التحقق من أن الطبيب هو نفسه طبيب الحجز
            if ($appointment->doctor_id !== $rating->doctor_id) {
                return false;
            }

            // التحقق من أن المريض هو نفسه مريض الحجز
            if ($appointment->patient_id !== $rating->patient_id) {
                return false;
            }

            return true;
        });
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * تنسيق تاريخ التقييم بشكل أفضل للعرض
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->locale('ar')->translatedFormat('d F Y - h:i A');
    }
}
