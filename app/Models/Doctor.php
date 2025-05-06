<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Traits\Searchable;
use DateTime;

class Doctor extends Model
{
    use HasFactory, Searchable;

    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'name',
        'bio',
        'description',
        'image',
        'governorate_id',
        'city_id',
        'address',
        'degree',
        'price',
        'rating',
        'waiting_time',
        'consultation_fee',
        'experience_years',
        'gender',
        'status',
        'title',
        'specialization'
    ];

    protected $searchable = [
        'name',
        'email',
        'phone',
        'bio',
        'description'
    ];

    public $timestamps = true;

    /**
     * Handle image upload and storage
     */
    public static function uploadImage($image)
    {
        if ($image) {
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            return $image->storeAs('doctors', $imageName, 'public');
        }
        return null;
    }

    /**
     * Delete the doctor's image from storage
     */
    public function deleteImage()
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }
    }

    /**
     * Get the image URL attribute
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // استخدام دالة asset مع المسار الكامل للصورة
            return asset('storage/' . $this->image);
        }
        return asset('images/default-doctor.png');
    }

    /**
     * Get the user that the doctor belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the doctor's name by combining first and last name.
     */
    public function getNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the doctor's email from the associated user.
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }

    /**
     * Get the doctor's phone from the associated user.
     */
    public function getPhoneAttribute()
    {
        return $this->user ? $this->user->phone_number : null;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'doctor_category')
                    ->withTimestamps();
    }

    /**
     * Get the governorate that the doctor belongs to.
     */
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    /**
     * Get the city that the doctor belongs to.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get all appointments for the doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function getAvailableSlots($date)
    {
        $dateTime = new DateTime($date);
        $dayOfWeek = strtolower($dateTime->format('l'));
        $schedule = $this->schedules()
            ->where('day', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        // If no schedule exists, return empty array
        if (!$schedule) {
            return [];
        }

        // Get slots from schedule
        $availableSlots = $schedule->getAvailableSlots(new DateTime($date));
        $dateStr = $dateTime->format('Y-m-d');

        // تحميل جميع المواعيد المحجوزة لهذا اليوم مسبقًا
        $bookedAppointments = $this->appointments()
            ->whereDate('scheduled_at', $dateStr)
            ->where('status', 'scheduled')
            ->get()
            ->map(function($appointment) {
                return $appointment->scheduled_at->format('H:i');
            })
            ->toArray();

        // استبعاد الأوقات التي تم حجزها بالفعل
        return array_values(array_filter($availableSlots, function($slot) use ($bookedAppointments) {
            return !in_array($slot, $bookedAppointments);
        }));
    }

    protected static function arabicToEnglishDay($arabicDay)
    {
        return match($arabicDay) {
            'الأحد' => 'sunday',
            'الإثنين' => 'monday',
            'الثلاثاء' => 'tuesday',
            'الأربعاء' => 'wednesday',
            'الخميس' => 'thursday',
            'الجمعة' => 'friday',
            'السبت' => 'saturday',
            default => strtolower($arabicDay)
        };
    }

    public function updateSchedule($scheduleData)
    {
        // Delete existing schedules
        $this->schedules()->delete();

        // Add only available schedules
        foreach ($scheduleData as $schedule) {
            // تحويل اسم اليوم إلى الإنجليزية إذا كان بالعربية
            $englishDay = isset($schedule['day']) ? self::arabicToEnglishDay($schedule['day']) : '';

            if (!empty($englishDay) && isset($schedule['is_available']) && $schedule['is_available'] &&
                isset($schedule['start_time']) && isset($schedule['end_time'])) {
                // إضافة اليوم المتاح فقط إلى جدول المواعيد
                $this->schedules()->create([
                    'day' => $englishDay,
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'is_active' => true
                ]);
            }
        }
    }

}
