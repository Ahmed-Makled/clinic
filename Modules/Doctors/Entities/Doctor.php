<?php

namespace Modules\Doctors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Traits\Searchable;
use Modules\Users\Entities\Governorate;
use Modules\Users\Entities\City;
use Modules\Users\Entities\User;
use Modules\Specialties\Entities\Category;
use Modules\Appointments\Entities\Appointment;
use Modules\Doctors\Entities\DoctorSchedule;
use Modules\Doctors\Entities\DoctorRating;
use DateTime;

class Doctor extends Model
{
    use HasFactory, Searchable;

    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'name',
        'description',       // نبذة تعريفية عن الطبيب
        'image',            // صورة الملف الشخصي
        'governorate_id',
        'city_id',
        'category_id',      // حقل التخصص الواحد
        'address',
        'degree',           // الدرجة العلمية: دكتوراه، ماجستير، بكالوريوس طب
        'waiting_time',      // متوسط وقت الانتظار للكشف بالدقائق
        'consultation_fee',  // رسوم الكشف
        'experience_years',  // عدد سنوات الخبرة
        'gender',           // الجنس: ذكر، أنثى
        'status',           // حالة الحساب: نشط أو غير نشط
        'title',            // المسمى الوظيفي: استشاري، أخصائي، طبيب
        'is_profile_completed',
        'rating_avg'
    ];

    protected $searchable = [
        'name',
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
        // المسار الافتراضي للصورة
        $defaultImagePath = 'images/default-doctor.png';

        // التحقق من وجود صورة محددة للطبيب
        if (!empty($this->image)) {
            $imagePath = 'storage/' . $this->image;

            // التحقق من وجود الملف فعليًا
            if (file_exists(public_path($imagePath))) {
                return asset($imagePath);
            }

            // إذا كان المسار موجود ولكن الملف غير موجود، نسجل هذا في السجلات
            \Illuminate\Support\Facades\Log::warning("صورة الطبيب غير موجودة: {$this->image} للطبيب رقم: {$this->id}");
        }

        // التحقق من وجود الصورة الافتراضية، وإلا استخدم صورة افتراضية أخرى
        if (file_exists(public_path($defaultImagePath))) {
            return asset($defaultImagePath);
        }

        // إذا لم تكن الصورة الافتراضية موجودة، استخدم صورة افتراضية من UI Avatars
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=random&color=fff&size=256";
    }

    /**
     * Get the user that the doctor belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the doctor's name from the associated user.
     */
    public function getNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }

        // إرجاع attributes['name'] إذا كان موجوداً، وإلا إرجاع قيمة افتراضية
        return $this->attributes['name'] ?? 'طبيب';
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

    /**
     * Get the category that the doctor belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @deprecated This method is kept for backward compatibility and will be removed in the future.
     * Use category() instead.
     *
     * This returns a collection containing the single category for compatibility with old code
     * that expects multiple categories
     */
    public function categories()
    {
        // Create a collection-like response with only the related category
        return $this->hasOne(Category::class, 'id', 'category_id');
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

        // تحميل جميع الحجوزات المحجوزة لهذا اليوم مسبقًا
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
                // إضافة اليوم المتاح فقط إلى جدول الحجوزات
                $this->schedules()->create([
                    'day' => $englishDay,
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'is_active' => true
                ]);
            }
        }
    }

    /**
     * الحصول على عدد التقييمات لهذا الطبيب
     */
    public function getRatingsCountAttribute()
    {
        return DoctorRating::where('doctor_id', $this->id)
            ->where('is_verified', true)
            ->count();
    }

    /**
     * الحصول على متوسط تقييم الطبيب
     */
    public function getRatingAvgAttribute()
    {
        return DoctorRating::where('doctor_id', $this->id)
            ->where('is_verified', true)
            ->avg('rating') ?? 0;
    }

    /**
     * الحصول على التقييمات المرتبة حسب عدد النجوم (5 إلى 1)
     */
    public function getRatingStatsAttribute()
    {
        $stats = [];
        $totalRatings = $this->ratings_count;

        for ($i = 5; $i >= 1; $i--) {
            $count = DoctorRating::where('doctor_id', $this->id)
                ->where('is_verified', true)
                ->where('rating', $i)
                ->count();

            $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;

            $stats[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return $stats;
    }

    /**
     * تحديد ما إذا كانت بيانات الطبيب مكتملة أم لا
     */
    public function isProfileCompleted()
    {
        // التحقق من وجود البيانات الأساسية
        $hasBasicInfo = !empty($this->title) &&
                        !empty($this->experience_years) &&
                        !empty($this->address) &&
                        !empty($this->governorate_id) &&
                        !empty($this->city_id) &&
                        !empty($this->consultation_fee) &&
                        !empty($this->waiting_time);

        // التحقق من وجود تخصص واحد
        $hasCategory = !empty($this->category_id);

        // التحقق من وجود جدول مواعيد
        $hasSchedule = $this->schedules()->count() > 0;

        $isCompleted = $hasBasicInfo && $hasCategory && $hasSchedule;

        // تحديث حالة اكتمال الملف الشخصي إذا كانت مختلفة عن القيمة المخزنة
        if ($this->is_profile_completed !== $isCompleted) {
            $this->is_profile_completed = $isCompleted;
            $this->save();
        }

        return $isCompleted;
    }

    /**
     * البيانات المتبقية لاستكمال الملف الشخصي
     */
    public function getMissingProfileDataAttribute()
    {
        $missingData = [];

        if (empty($this->title)) {
            $missingData[] = 'المسمى الوظيفي';
        }

        if (empty($this->experience_years)) {
            $missingData[] = 'سنوات الخبرة';
        }

        if (empty($this->address)) {
            $missingData[] = 'العنوان';
        }

        if (empty($this->governorate_id)) {
            $missingData[] = 'المحافظة';
        }

        if (empty($this->city_id)) {
            $missingData[] = 'المدينة';
        }

        if (empty($this->consultation_fee)) {
            $missingData[] = 'رسوم الاستشارة';
        }

        if (empty($this->waiting_time)) {
            $missingData[] = 'وقت الانتظار';
        }

        // التحقق من وجود تخصص واحد
        if (empty($this->category_id)) {
            $missingData[] = 'التخصص';
        }

        if ($this->schedules()->count() == 0) {
            $missingData[] = 'جدول المواعيد';
        }

        return $missingData;
    }
}
