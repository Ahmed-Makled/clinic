<?php

namespace Modules\Doctors\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Doctors\Entities\Doctor;
use Modules\Doctors\Entities\DoctorRating;
use Modules\Doctors\Entities\DoctorSchedule;
use Modules\Specialties\Entities\Category;
use Modules\Users\Entities\Governorate;
use Modules\Users\Entities\User;
use Modules\Appointments\Entities\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Modules\Doctors\Notifications\NewDoctorNotification;
use Modules\Doctors\Notifications\DoctorUpdatedNotification;
use Modules\Doctors\Notifications\DoctorDeletedNotification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\AppointmentCompletedNotification;
use Modules\Appointments\Notifications\AppointmentCancelledNotification;

class DoctorsController extends Controller
{


    protected function applyFilters($query, Request $request)
    {
        // تطبيق البحث
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // تطبيق فلتر التخصص
        if ($request->filled('category') || $request->filled('category_filter')) {
            $categoryId = $request->category ?? $request->category_filter;
            $query->where('category_id', $categoryId);
        }

        // فلتر المحافظة
        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        // فلتر المدينة
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // فلتر سنوات الخبرة
        if ($request->filled('experience')) {
            [$min, $max] = explode('-', $request->experience);
            if ($max === '+') {
                $query->where('experience_years', '>=', $min);
            } else {
                $query->whereBetween('experience_years', [$min, $max]);
            }
        }

        // فلتر سعر الكشف
        if ($request->filled('fee_range')) {
            [$min, $max] = explode('-', $request->fee_range);
            if ($max === '+') {
                $query->where('consultation_fee', '>=', $min);
            } else {
                $query->whereBetween('consultation_fee', [$min, $max]);
            }
        }

        // فلتر الحالة
        if ($request->filled('status_filter')) {
            $status = $request->status_filter === '1';
            $query->where('status', $status);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = Doctor::with(['categories', 'user', 'governorate', 'city']);
        $query = $this->applyFilters($query, $request);

        $doctors = $query->latest()->paginate(10);
        $categories = Category::active()->get();
        $governorates = Governorate::all();

        // إحصائيات الأطباء للعرض في الواجهة
        $stats = [
            'doctors' => [
                'total' => Doctor::count(),
                'active' => Doctor::where('status', true)->count(),
                'incomplete' => Doctor::where(function($query) {
                                $query->where('is_profile_completed', false)
                                     ->orWhereNull('is_profile_completed');
                            })->count()
            ]
        ];

        return view('doctors::admin.index', [
            'title' => 'إدارة الأطباء - Clinic Master',
            'doctors' => $doctors,
            'categories' => $categories,
            'governorates' => $governorates,
            'stats' => $stats
        ]);
    }

    public function create()
    {
        $categories = Category::active()->get();
        $governorates = Governorate::with('cities')->get();
        return view('doctors::admin.create', [
            'title' => 'إضافة طبيب جديد - Clinic Master',
            'categories' => $categories,
            'governorates' => $governorates
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:8'],
            'title' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'], // تعديل من مصفوفة تخصصات إلى تخصص واحد
            'gender' => ['required', Rule::in(['ذكر', 'انثي'])],
            'experience_years' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'schedules' => ['required', 'array'],
            'schedules.*.is_available' => ['nullable', 'boolean'],
            'schedules.*.start_time' => [
                'required_with:schedules.*.is_available',
                'date_format:H:i',
            ],
            'schedules.*.end_time' => [
                'required_with:schedules.*.is_available',
                'date_format:H:i',
                'after:schedules.*.start_time'
            ],
            'status' => ['boolean'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'waiting_time' => ['nullable', 'integer', 'min:0'],
            'degree' => ['nullable', 'string', 'max:100']
        ], [
            'name.required' => 'اسم الطبيب مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'title.required' => 'المسمى الوظيفي مطلوب',
            'category_id.required' => 'التخصص مطلوب', // تعديل الرسالة
            'category_id.exists' => 'التخصص المختار غير موجود', // تعديل الرسالة
            'gender.required' => 'النوع مطلوب',
            'experience_years.required' => 'سنوات الخبرة مطلوبة',
            'experience_years.integer' => 'سنوات الخبرة يجب أن تكون رقماً صحيحاً',
            'experience_years.min' => 'سنوات الخبرة يجب أن تكون أكبر من صفر',
            'description.max' => 'الوصف لا يمكن أن يتجاوز 1000 حرف',
            'image.image' => 'الملف المرفق يجب أن يكون صورة',
            'image.mimes' => 'صيغة الصورة غير مدعومة',
            'image.max' => 'حجم الصورة لا يمكن أن يتجاوز 2 ميجابايت',
            'address.required' => 'العنوان مطلوب',
            'governorate_id.required' => 'المحافظة مطلوبة',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة',
            'city_id.required' => 'المدينة مطلوبة',
            'city_id.exists' => 'المدينة المختارة غير موجودة',
            'status.boolean' => 'حالة الحساب يجب أن تكون صحيحة أو خاطئة',
            'consultation_fee.required' => 'سعر الكشف مطلوب',
            'consultation_fee.numeric' => 'سعر الكشف يجب أن يكون رقماً',
            'consultation_fee.min' => 'سعر الكشف يجب أن يكون أكبر من صفر',
            'waiting_time.required' => 'مدة الانتظار مطلوبة',
            'waiting_time.integer' => 'مدة الانتظار يجب أن تكون رقماً صحيحاً',
            'waiting_time.min' => 'مدة الانتظار يجب أن تكون أكبر من صفر',
            'degree.max' => 'الدرجة العلمية لا يمكن أن تتجاوز 100 حرف'
        ]);

        try {
            DB::beginTransaction();

            // إنشاء حساب المستخدم
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone_number' => $validated['phone'],
                'type' => 'doctor',
                'status' => true
            ]);

            $user->assignRole('Doctor');

            // التحقق من وجود جميع الحقول المطلوبة لتحديد اكتمال الملف الشخصي
            $hasAllRequiredFields = !empty($validated['title']) &&
                                   !empty($validated['experience_years']) &&
                                   !empty($validated['address']) &&
                                   !empty($validated['governorate_id']) &&
                                   !empty($validated['city_id']) &&
                                   !empty($validated['consultation_fee']) &&
                                   !empty($validated['waiting_time']) &&
                                   isset($validated['category_id']); // تعديل التحقق من التخصصات

            // تحديد قيمة حقل اكتمال الملف الشخصي
            $isProfileCompleted = $hasAllRequiredFields &&
                                 (isset($request->schedules) &&
                                 count(array_filter($request->schedules, function($schedule) {
                                     return isset($schedule['is_available']) && $schedule['is_available'];
                                 })) > 0);

            // Create doctor
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'consultation_fee' => $validated['consultation_fee'],
                'waiting_time' => $validated['waiting_time'],
                'experience_years' => $validated['experience_years'],
                'gender' => $validated['gender'],
                'status' => true,
                'address' => $validated['address'],
                'governorate_id' => $validated['governorate_id'],
                'city_id' => $validated['city_id'],
                'category_id' => $validated['category_id'], // إضافة حقل التخصص
                'is_profile_completed' => $isProfileCompleted
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $doctor->image = Doctor::uploadImage($request->file('image'));
                $doctor->save();
            }

            // Add schedules
            $days = [
                'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'
            ];
            $scheduleData = [];

            foreach ($request->schedules as $index => $schedule) {
                if (isset($schedule['is_available']) && $schedule['is_available']) {
                    $scheduleData[] = [
                        'day' => $days[$index],
                        'is_available' => true,
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time']
                    ];
                }
            }

            $doctor->updateSchedule($scheduleData);

            // Send notification to admin
            User::role('Admin')->each(function ($admin) use ($doctor) {
                $admin->notify(new NewDoctorNotification($doctor));
            });

            DB::commit();

            // التحقق إذا كانت البيانات مكتملة
            if (!$isProfileCompleted) {
                // إذا كانت البيانات غير مكتملة، اعرض رسالة تنبيه للمستخدم
                return redirect()->route('doctors.edit', $doctor->id)
                    ->with('warning', 'تم إضافة الطبيب لكن يجب استكمال بياناته لتفعيل حسابه بشكل كامل');
            }

            return redirect()->route('doctors.index')->with('success', 'تم إضافة الطبيب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating doctor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة الطبيب. ' . $e->getMessage());
        }
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load(['schedules']); // تحميل جداول الحجوزات
        $categories = Category::all();
        $governorates = Governorate::with('cities')->get();
        return view('doctors::admin.edit', [
            'title' => 'تعديل بيانات الطبيب - Clinic Master',
            'doctor' => $doctor,
            'categories' => $categories,
            'governorates' => $governorates
        ]);
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'], // تعديل من مصفوفة تخصصات إلى تخصص واحد
            'gender' => ['required', Rule::in(['ذكر', 'انثي'])],
            'experience_years' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'status' => ['required', 'boolean'],  // تحديث التحقق من حقل status
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'waiting_time' => ['required', 'integer', 'min:0'],
            'schedules' => ['required', 'array'],
            'schedules.*.is_available' => ['nullable', 'boolean'],
            'schedules.*.start_time' => [
                'required_with:schedules.*.is_available',
                'date_format:H:i',
            ],
            'schedules.*.end_time' => [
                'required_with:schedules.*.is_available',
                'date_format:H:i',
                'after:schedules.*.start_time'
            ],
            'degree' => ['nullable', 'string', 'max:100']
        ], [
            'title.required' => 'المسمى الوظيفي مطلوب',
            'category_id.required' => 'التخصص مطلوب', // تعديل الرسالة
            'category_id.exists' => 'التخصص المختار غير موجود', // تعديل الرسالة
            'gender.required' => 'النوع مطلوب',
            'experience_years.required' => 'سنوات الخبرة مطلوبة',
            'experience_years.integer' => 'سنوات الخبرة يجب أن تكون رقماً صحيحاً',
            'experience_years.min' => 'سنوات الخبرة يجب أن تكون أكبر من صفر',
            'description.max' => 'الوصف لا يمكن أن يتجاوز 1000 حرف',
            'image.image' => 'الملف المرفق يجب أن يكون صورة',
            'image.mimes' => 'صيغة الصورة غير مدعومة',
            'image.max' => 'حجم الصورة لا يمكن أن يتجاوز 2 ميجابايت',
            'address.required' => 'العنوان مطلوب',
            'governorate_id.required' => 'المحافظة مطلوبة',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة',
            'city_id.required' => 'المدينة مطلوبة',
            'city_id.exists' => 'المدينة المختارة غير موجودة',
            'status.required' => 'حالة الحساب مطلوبة',
            'status.boolean' => 'حالة الحساب يجب أن تكون صحيحة أو خاطئة',
            'consultation_fee.required' => 'سعر الكشف مطلوب',
            'consultation_fee.numeric' => 'سعر الكشف يجب أن يكون رقماً',
            'consultation_fee.min' => 'سعر الكشف يجب أن يكون أكبر من صفر',
            'waiting_time.required' => 'مدة الانتظار مطلوبة',
            'waiting_time.integer' => 'مدة الانتظار يجب أن تكون رقماً صحيحاً',
            'waiting_time.min' => 'مدة الانتظار يجب أن تكون أكبر من صفر',
            'schedules.*.start_time.required_with' => 'يجب تحديد وقت البداية عند اختيار اليوم',
            'schedules.*.start_time.date_format' => 'صيغة وقت البداية غير صحيحة',
            'schedules.*.end_time.required_with' => 'يجب تحديد وقت النهاية عند اختيار اليوم',
            'schedules.*.end_time.date_format' => 'صيغة وقت النهاية غير صحيحة',
            'schedules.*.end_time.after' => 'يجب أن يكون وقت النهاية بعد وقت البداية',
            'degree.max' => 'الدرجة العلمية لا يمكن أن تتجاوز 100 حرف'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $doctor->deleteImage();
                $doctor->image = Doctor::uploadImage($request->file('image'));
            }

            $doctor->update([
                'title' => $validated['title'],
                'experience_years' => $validated['experience_years'],
                'description' => $validated['description'],
                'consultation_fee' => $validated['consultation_fee'],
                'waiting_time' => $validated['waiting_time'] ?? 30,
                'gender' => $validated['gender'],
                'status' => $request->boolean('status'),  // التأكد من تحويل القيمة إلى boolean
                'address' => $validated['address'],
                'governorate_id' => $validated['governorate_id'],
                'city_id' => $validated['city_id'],
                'category_id' => $validated['category_id'], // تحديث حقل التخصص
                'degree' => $validated['degree'] ?? null
            ]);

            // تحديث جداول المواعيد
            $days = [
                'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'
            ];
            $scheduleData = [];

            foreach ($request->schedules as $index => $schedule) {
                if (isset($schedule['is_available']) && $schedule['is_available']) {
                    $scheduleData[] = [
                        'day' => $days[$index],
                        'is_available' => true,
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time']
                    ];
                }
            }

            $doctor->updateSchedule($scheduleData);

            // Send notification to admins
            User::role('Admin')->each(function ($admin) use ($doctor) {
                $admin->notify(new DoctorUpdatedNotification($doctor));
            });

            // التحقق من وجود معلمة redirect في URL
            if ($request->has('redirect')) {
                return redirect($request->redirect)
                    ->with('success', 'تم تحديث بيانات الطبيب بنجاح');
            }

            // التحقق من وجود معلمة redirect_to في URL
            if ($request->has('redirect_to')) {
                return redirect($request->redirect_to)
                    ->with('success', 'تم تحديث بيانات الطبيب بنجاح');
            }

            // التحقق من إذا كان الطلب قادما من صفحة المستخدمين
            if ($request->has('redirect') && $request->redirect === 'users') {
                return redirect()->route('users.index')
                    ->with('success', 'تم اضافة المستخدم واستكمال البيانات بنجاح');
            }

            // التحقق من وجود URL مصدر في رأس الطلب HTTP_REFERER
            if ($request->header('referer') && strpos($request->header('referer'), '/doctors/') === false) {
                return redirect($request->header('referer'))
                    ->with('success', 'تم تحديث بيانات الطبيب بنجاح');
            }

            return redirect()->route('doctors.index')
                ->with('success', 'تم تحديث بيانات الطبيب بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error updating doctor: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات الطبيب: ' . $e->getMessage());
        }
    }

    public function destroy(Doctor $doctor)
    {
        $doctorName = $doctor->name;

        // Get the user ID before deleting the doctor
        $userId = $doctor->user_id;

        $doctor->delete();

        // Delete the associated user if it exists
        if ($userId) {
            User::where('id', $userId)->delete();
        }

        // Send notification to admins
        User::role('Admin')->each(function ($admin) use ($doctorName) {
            $admin->notify(new DoctorDeletedNotification($doctorName));
        });

        return redirect()->route('doctors.index')
            ->with('success', 'تم حذف الطبيب بنجاح');
    }

    /**
     * Display the doctor's detailed information for admin.
     */
    public function details(Doctor $doctor)
    {
        $doctor->load([
            'category', // Cambiar de categories a category (singular)
            'user',
            'governorate',
            'city',
            'schedules' // إضافة تحميل جداول الحجوزات
        ]);

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('scheduled_at', now())
            ->with(['patient'])
            ->orderBy('scheduled_at')
            ->get();

        // إحصائيات الحجوزات
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;

        $completedAppointments = $doctor->appointments()
            ->whereMonth('scheduled_at', $currentMonth)
            ->where('status', 'completed')
            ->count();

        $cancelledAppointments = $doctor->appointments()
            ->whereMonth('scheduled_at', $currentMonth)
            ->where('status', 'cancelled')
            ->count();

        // مقارنة الحجوزات مع الشهر السابق
        $lastMonthCompleted = $doctor->appointments()
            ->whereMonth('scheduled_at', $lastMonth)
            ->where('status', 'completed')
            ->count();

        $completedGrowthRate = $lastMonthCompleted > 0
            ? (($completedAppointments - $lastMonthCompleted) / $lastMonthCompleted) * 100
            : 0;

        // حساب متوسط أسعار الكشف في نفس التخصص
        $averageConsultationFee = Doctor::where('category_id', $doctor->category_id) // Usar category_id
            ->where('id', '!=', $doctor->id)
            ->avg('consultation_fee') ?? 0;

        $feeComparisonRate = $averageConsultationFee > 0
            ? (($doctor->consultation_fee - $averageConsultationFee) / $averageConsultationFee) * 100
            : 0;

        // حساب إجمالي الإيرادات وتحليلها
        $totalEarnings = $doctor->appointments()
            ->where('status', 'completed')
            ->whereHas('payment', function($query) {
                $query->where('status', 'completed');
            })
            ->sum('fees');

        $lastMonthEarnings = $doctor->appointments()
            ->whereMonth('scheduled_at', $lastMonth)
            ->where('status', 'completed')
            ->whereHas('payment', function($query) {
                $query->where('status', 'completed');
            })
            ->sum('fees');

        $earningsGrowthRate = $lastMonthEarnings > 0
            ? (($totalEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100
            : 0;

        // تنسيق أيام الأسبوع بالعربية
        foreach ($doctor->schedules as $schedule) {
            $schedule->day_name = match($schedule->day) {
                'sunday' => 'الأحد',
                'monday' => 'الإثنين',
                'tuesday' => 'الثلاثاء',
                'wednesday' => 'الأربعاء',
                'thursday' => 'الخميس',
                'friday' => 'الجمعة',
                'saturday' => 'السبت',
                default => $schedule->day
            };
        }

        // تحميل تقييمات الدكتور
        $ratings = DoctorRating::where('doctor_id', $doctor->id)
            ->where('is_verified', true)
            ->with('patient.user')
            ->latest()
            ->limit(10)
            ->get();

        // حساب إجمالي عدد التقييمات
        $ratingsCount = DoctorRating::where('doctor_id', $doctor->id)
            ->where('is_verified', true)
            ->count();

        // حساب متوسط التقييم
        $avgRating = DoctorRating::where('doctor_id', $doctor->id)
            ->where('is_verified', true)
            ->avg('rating') ?? 0;

        // حساب عدد التقييمات لكل نجمة
        $ratingStats = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = DoctorRating::where('doctor_id', $doctor->id)
                ->where('is_verified', true)
                ->where('rating', $i)
                ->count();

            $percentage = $ratingsCount > 0 ? ($count / $ratingsCount) * 100 : 0;

            $ratingStats[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return view('doctors::admin.details', [
            'title' => 'تفاصيل الطبيب - Clinic Master',
            'doctor' => $doctor,
            'appointments' => $appointments,
            'completedAppointments' => $completedAppointments,
            'cancelledAppointments' => $cancelledAppointments,
            'completedGrowthRate' => $completedGrowthRate,
            'feeComparisonRate' => $feeComparisonRate,
            'totalEarnings' => $totalEarnings,
            'earningsGrowthRate' => $earningsGrowthRate,
            'ratings' => $ratings,
            'ratingsCount' => $ratingsCount,
            'avgRating' => $avgRating,
            'ratingStats' => $ratingStats
        ]);
    }

    public function search(Request $request)
    {
        $query = Doctor::with(['categories', 'governorate', 'city'])
            ->whereHas('user', function ($q) {
                $q->where('status', true);
            })
            ->where('is_profile_completed', true); // Only show doctors with complete profiles

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        $doctors = $query->latest()->paginate(12);
        $categories = Category::where('status', true)->get();
        $governorates = Governorate::all();

        return view('doctors::search', [
            'title' => 'البحث عن الأطباء - Clinic Master',
            'doctors' => $doctors,
            'categories' => $categories,
            'governorates' => $governorates
        ]);
    }

    protected function handleImageUpdate(Doctor $doctor, $newImage)
    {
        if ($newImage) {
            // حذف الصورة القديمة
            if ($doctor->image) {
                Storage::disk('public')->delete($doctor->image);
            }

            // رفع وحفظ الصورة الجديدة
            $doctor->image = Doctor::uploadImage($newImage);
            $doctor->save();
        }
    }

    protected function calculateStatistics(Doctor $doctor)
    {
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;

        $stats = [
            'completedAppointments' => $doctor->appointments()
                ->whereMonth('scheduled_at', $currentMonth)
                ->where('status', 'completed')
                ->count(),

            'cancelledAppointments' => $doctor->appointments()
                ->whereMonth('scheduled_at', $currentMonth)
                ->where('status', 'cancelled')
                ->count(),
        ];

        // حساب معدل النمو
        $lastMonthCompleted = $doctor->appointments()
            ->whereMonth('scheduled_at', $lastMonth)
            ->where('status', 'completed')
            ->count();

        $stats['completedGrowthRate'] = $this->calculateGrowthRate(
            $stats['completedAppointments'],
            $lastMonthCompleted
        );

        // حساب متوسط أسعار الكشف في نفس التخصص
        $stats['averageConsultationFee'] = Doctor::where('category_id', $doctor->category_id)
            ->where('id', '!=', $doctor->id)
            ->avg('consultation_fee') ?? 0;

        return $stats;
    }

    protected function calculateGrowthRate($current, $previous)
    {
        if ($previous <= 0) {
            return 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    /**
     * Rate a doctor and store the rating.
     *
     * @param Request $request
     * @param Doctor $doctor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rate(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'appointment_id' => 'required|exists:appointments,id'
        ], [
            'rating.required' => 'التقييم مطلوب',
            'rating.integer' => 'يجب أن يكون التقييم رقمًا صحيحًا',
            'rating.min' => 'يجب أن يكون التقييم 1 على الأقل',
            'rating.max' => 'يجب أن لا يتجاوز التقييم 5',
            'comment.max' => 'التعليق يجب أن لا يتجاوز 500 حرف',
        ]);

        // التحقق من أن المستخدم الحالي هو صاحب الحجز
        $appointment = Appointment::find($validated['appointment_id']);

        // التحقق من وجود الحجز وأن المستخدم هو صاحب الحجز
        if (!$appointment || $appointment->patient_id != auth()->user()->patient->id) {
            return back()->with('error', 'لا يمكنك تقييم هذا الطبيب لهذا الحجز');
        }

        // التحقق من اكتمال الحجز قبل السماح بالتقييم
        if ($appointment->status != 'completed') {
            return back()->with('error', 'لا يمكن تقييم الطبيب إلا بعد اكتمال الزيارة');
        }

        // التحقق من أن الطبيب المراد تقييمه هو نفس طبيب الحجز
        if ($appointment->doctor_id != $doctor->id) {
            return back()->with('error', 'لا يمكنك تقييم طبيب مختلف عن طبيب الحجز');
        }

        // التحقق من عدم وجود تقييم سابق لنفس الحجز
        $existingRating = DoctorRating::where('doctor_id', $doctor->id)
            ->where('patient_id', auth()->user()->patient->id)
            ->where('appointment_id', $validated['appointment_id'])
            ->first();

        if ($existingRating) {
            // تحديث التقييم الموجود
            $existingRating->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            $message = 'تم تحديث تقييمك بنجاح';
        } else {
            // إنشاء تقييم جديد
            DoctorRating::create([
                'doctor_id' => $doctor->id,
                'patient_id' => auth()->user()->patient->id,
                'appointment_id' => $validated['appointment_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'is_verified' => true,
            ]);

            $message = 'تم إضافة تقييمك بنجاح';
        }

        // تحديث متوسط تقييم الطبيب
        $this->updateDoctorRating($doctor);

        // الرجوع للصفحة السابقة مع تحديد تبويب الحجوزات بشكل نشط
        if ($request->header('Referer') && strpos($request->header('Referer'), 'profile') !== false) {
            // إذا كنا في صفحة الملف الشخصي، نرجع لنفس الصفحة مع تحديد قسم الحجوزات
            return redirect()->route('profile', ['#appointments'])->with('success', $message);
        }

        // إذا لم نكن في صفحة الملف الشخصي، نرجع للصفحة السابقة
        return back()->with('success', $message);
    }

    /**
     * Update doctor's average rating.
     *
     * @param Doctor $doctor
     * @return void
     */
    private function updateDoctorRating(Doctor $doctor)
    {
        $avgRating = DoctorRating::where('doctor_id', $doctor->id)
            ->where('is_verified', true)
            ->avg('rating') ?? 0;

        $doctor->update(['rating_avg' => $avgRating]);
    }

    /**
     * Display the doctor's profile page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function profile()
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $doctor->load([
            'categories',
            'governorate',
            'city',
            'schedules',
            'appointments' => function ($query) {
                $query->with(['patient.user'])
                    ->whereDate('scheduled_at', '>=', now())
                    ->orderBy('scheduled_at');
            }
        ]);

        // تنسيق أيام الأسبوع بالعربية
        foreach ($doctor->schedules as $schedule) {
            $schedule->day_name = match($schedule->day) {
                'sunday' => 'الأحد',
                'monday' => 'الإثنين',
                'tuesday' => 'الثلاثاء',
                'wednesday' => 'الأربعاء',
                'thursday' => 'الخميس',
                'friday' => 'الجمعة',
                'saturday' => 'السبت',
                default => $schedule->day
            };
        }

        // إحصائيات الحجوزات
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;

        $stats = [
            'today_appointments' => $doctor->appointments()
                ->whereDate('scheduled_at', now()->toDateString())
                ->count(),

            'upcoming_appointments' => $doctor->appointments()
                ->whereDate('scheduled_at', '>', now()->toDateString())
                ->where('status', 'scheduled')
                ->count(),

            'completed_appointments' => $doctor->appointments()
                ->whereMonth('scheduled_at', $currentMonth)
                ->where('status', 'completed')
                ->count(),

            'cancelled_appointments' => $doctor->appointments()
                ->whereMonth('scheduled_at', $currentMonth)
                ->where('status', 'cancelled')
                ->count(),
        ];

        $governorates = Governorate::with('cities')->get();
        $categories = Category::active()->get();

        return view('doctors::profile', [
            'title' => 'الملف الشخصي - Clinic Master',
            'doctor' => $doctor,
            'stats' => $stats,
            'governorates' => $governorates,
            'categories' => $categories
        ]);
    }

    /**
     * Update the doctor's profile information
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'phone' => ['required', 'string', 'unique:users,phone_number,'.$user->id],
            'title' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'experience_years' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'waiting_time' => ['nullable', 'integer', 'min:0'],
            'schedules' => ['required', 'array'],
            'schedules.*.is_available' => ['nullable', 'boolean'],
            'schedules.*.start_time' => [
                'required_with:schedules.*.is_available',
                'date_format:H:i',
            ],
            'schedules.*.end_time' => [
                'required_with:schedules.*.is_available',
                'date_format:H:i',
                'after:schedules.*.start_time'
            ]
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'title.required' => 'المسمى الوظيفي مطلوب',
            'category_id.required' => 'التخصص مطلوب',
            'experience_years.required' => 'سنوات الخبرة مطلوبة',
            'address.required' => 'العنوان مطلوب',
            'consultation_fee.required' => 'سعر الكشف مطلوب',
            'schedules.*.end_time.after' => 'يجب أن يكون وقت النهاية بعد وقت البداية',
        ]);

        try {
            DB::beginTransaction();

            // Update user information
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone']
            ]);

            // Update doctor info
            $doctor->update([
                'name' => $validated['name'],
                'title' => $validated['title'],
                'experience_years' => $validated['experience_years'],
                'description' => $validated['description'],
                'consultation_fee' => $validated['consultation_fee'],
                'waiting_time' => $validated['waiting_time'] ?? 30,
                'address' => $validated['address'],
                'governorate_id' => $validated['governorate_id'],
                'city_id' => $validated['city_id'],
                // Keep the existing status value instead of reading from the form
                // status field is not modified by the doctor
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $doctor->deleteImage();
                $doctor->image = Doctor::uploadImage($request->file('image'));
                $doctor->save();
            }

            // Update category
            if (!empty($validated['category_id'])) {
                $doctor->update(['category_id' => $validated['category_id']]);
            }

            // Update schedules
            $days = [
                'sunday' => 'الأحد',
                'monday' => 'الإثنين',
                'tuesday' => 'الثلاثاء',
                'wednesday' => 'الأربعاء',
                'thursday' => 'الخميس',
                'friday' => 'الجمعة',
                'saturday' => 'السبت'
            ];

            $scheduleData = [];
            foreach ($request->schedules as $index => $schedule) {
                $dayKey = array_keys($days)[$index];
                $scheduleData[] = [
                    'day' => $dayKey,
                    'is_available' => isset($schedule['is_available']) && $schedule['is_available'] ? true : false,
                    'start_time' => isset($schedule['start_time']) ? $schedule['start_time'] : null,
                    'end_time' => isset($schedule['end_time']) ? $schedule['end_time'] : null
                ];
            }

            $doctor->updateSchedule($scheduleData);

            DB::commit();

            return redirect()->route('doctors.profile')
                ->with('success', 'تم تحديث بيانات الحساب بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage());
        }
    }

    /**
     * Update the doctor's password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('كلمة المرور الحالية غير صحيحة');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.min' => 'كلمة المرور الجديدة يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق'
        ]);

        try {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->route('doctors.profile')
                ->with('success', 'تم تحديث كلمة المرور بنجاح');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'حدث خطأ أثناء تحديث كلمة المرور');
        }
    }

    /**
     * Display the doctor's appointments
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function appointments(Request $request)
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $query = Appointment::where('doctor_id', $doctor->id)
            ->with(['patient.user']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by specific date
        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        // Filter by date range
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('scheduled_at', Carbon::today());
                    break;
                case 'tomorrow':
                    $query->whereDate('scheduled_at', Carbon::tomorrow());
                    break;
                case 'this_week':
                    $query->whereBetween('scheduled_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]);
                    break;
                case 'next_week':
                    $query->whereBetween('scheduled_at', [
                        Carbon::now()->addWeek()->startOfWeek(),
                        Carbon::now()->addWeek()->endOfWeek(),
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('scheduled_at', Carbon::now()->month)
                          ->whereYear('scheduled_at', Carbon::now()->year);
                    break;
                case 'custom':
                    if ($request->filled(['date_from', 'date_to'])) {
                        $query->whereBetween('scheduled_at', [
                            Carbon::parse($request->date_from)->startOfDay(),
                            Carbon::parse($request->date_to)->endOfDay(),
                        ]);
                    }
                    break;
            }
        }

        // Filter by patient name
        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->patient_name . '%');
            });
        }

        // Filter by appointment ID
        if ($request->filled('appointment_id')) {
            $query->where('id', $request->appointment_id);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $isPaid = $request->payment_status === 'paid';
            if ($isPaid) {
                $query->whereHas('payment', function ($q) {
                    $q->where('status', 'completed');
                });
            } else {
                $query->whereDoesntHave('payment', function ($q) {
                    $q->where('status', 'completed');
                });
            }
        }

        $appointments = $query->orderBy('scheduled_at')->paginate(15);

        return view('doctors::appointments', [
            'title' => 'المواعيد - Clinic Master',
            'doctor' => $doctor,
            'appointments' => $appointments
        ]);
    }

    /**
     * Mark an appointment as completed
     *
     * @param \Modules\Appointments\Entities\Appointment $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeAppointment(Appointment $appointment)
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        // Check if the appointment belongs to the logged in doctor
        if ($appointment->doctor_id !== $doctor->id) {
            return redirect()->route('doctors.profile')
                ->with('error', 'ليس لديك صلاحية للوصول إلى هذا الحجز')
                ->with('redirect_to_hash', 'appointments');
        }

        try {
            // التحقق من أن موعد الحجز قد حان أو مر
            $now = Carbon::now();
            $appointmentTime = $appointment->scheduled_at;

            if ($now->isBefore($appointmentTime)) {
                $timeRemaining = $appointmentTime->diffForHumans($now);
                return redirect()->back()->with('error', "لا يمكن إتمام الحجز قبل موعد الحجز المحدد. الموعد {$timeRemaining}");
            }

            $appointment->update(['status' => 'completed']);

            // Notify patient - use the updated appointment object directly
            $appointment->patient->user->notify(new AppointmentCompletedNotification($appointment));

            // Check the source parameter to determine redirect destination
            $source = request()->input('source');
            if ($source === 'profile_appointments') {
                return redirect()->route('doctors.profile')
                    ->with('success', 'تم تحديث الحجز كمكتمل بنجاح')
                    ->with('redirect_to_hash', 'appointments');
            } else {
                return redirect()->route('doctors.appointments')
                    ->with('success', 'تم تحديث الحجز كمكتمل بنجاح');
            }
        } catch (\Exception $e) {
            // Check the source parameter to determine redirect destination
            $source = request()->input('source');
            if ($source === 'profile_appointments') {
                return redirect()->route('doctors.profile')
                    ->with('error', 'حدث خطأ أثناء تحديث حالة الحجز: ' . $e->getMessage())
                    ->with('redirect_to_hash', 'appointments');
            } else {
                return redirect()->route('doctors.appointments')
                    ->with('error', 'حدث خطأ أثناء تحديث حالة الحجز: ' . $e->getMessage());
            }
        }
    }

    /**
     * Mark an appointment as cancelled
     *
     * @param \Modules\Appointments\Entities\Appointment $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelAppointment(Appointment $appointment)
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        // Check if the appointment belongs to the logged in doctor
        if ($appointment->doctor_id !== $doctor->id) {
            return redirect()->route('doctors.profile')
                ->with('error', 'ليس لديك صلاحية للوصول إلى هذا الحجز')
                ->with('redirect_to_hash', 'appointments');
        }

        try {
            $appointment->update(['status' => 'cancelled']);

            // Notify patient
            $appointment->patient->user->notify(new AppointmentCancelledNotification($appointment));

            // Check the source parameter to determine redirect destination
            $source = request()->input('source');
            if ($source === 'profile_appointments') {
                return redirect()->route('doctors.profile')
                    ->with('success', 'تم إلغاء الحجز بنجاح')
                    ->with('redirect_to_hash', 'appointments');
            } else {
                return redirect()->route('doctors.appointments')
                    ->with('success', 'تم إلغاء الحجز بنجاح');
            }
        } catch (\Exception $e) {
            // Check the source parameter to determine redirect destination
            $source = request()->input('source');
            if ($source === 'profile_appointments') {
                return redirect()->route('doctors.profile')
                    ->with('error', 'حدث خطأ أثناء إلغاء الحجز: ' . $e->getMessage())
                    ->with('redirect_to_hash', 'appointments');
            } else {
                return redirect()->route('doctors.appointments')
                    ->with('error', 'حدث خطأ أثناء إلغاء الحجز: ' . $e->getMessage());
            }
        }
    }

    // Methods for manual payment marking removed as clinic only uses Stripe payments

    /**
     * عرض الأطباء الذين لم يكملوا بياناتهم
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function incompleteProfiles(Request $request)
    {
        $query = Doctor::with(['categories', 'user', 'schedules'])
            ->where('is_profile_completed', false)
            ->orWhereNull('is_profile_completed');

        // تطبيق الفلاتر إذا وجدت
        $query = $this->applyFilters($query, $request);

        $doctors = $query->latest()->paginate(10);

        // حساب البيانات الناقصة لكل طبيب
        foreach ($doctors as $doctor) {
            $missingData = [];

            // if (!$doctor->image) {
            //     $missingData[] = 'صورة الطبيب';
            // }

            if (empty($doctor->category_id)) {
                $missingData[] = 'التخصصات';
            }

            // فحص جدول المواعيد بشكل أكثر دقة
            if ($doctor->schedules->isEmpty() || $doctor->schedules->where('is_active', true)->count() === 0) {
                $missingData[] = 'جدول المواعيد';
            }

            if (!$doctor->consultation_fee || $doctor->consultation_fee <= 0) {
                $missingData[] = 'رسوم الاستشارة';
            }

            if (!$doctor->waiting_time || $doctor->waiting_time <= 0) {
                $missingData[] = 'وقت الانتظار';
            }

            if (empty($doctor->address) || !$doctor->governorate_id || !$doctor->city_id) {
                $missingData[] = 'العنوان';
            }

            // Asignar missing_data como propiedad virtual, no para guardar en la base de datos
            $doctor->setAttribute('missing_data', $missingData);

            // تحديث حقل اكتمال الملف الشخصي إذا كانت البيانات مكتملة ولكن الحقل يشير إلى غير ذلك
            if (empty($missingData) && (!$doctor->is_profile_completed || is_null($doctor->is_profile_completed))) {
                // Actualizar solo el campo is_profile_completed
                Doctor::where('id', $doctor->id)->update(['is_profile_completed' => true]);
            }
        }

        $categories = Category::active()->get();

        return view('doctors::admin.incomplete_profiles', [
            'title' => 'الملفات غير المكتملة - Clinic Master',
            'doctors' => $doctors,
            'categories' => $categories
        ]);
    }

    /**
     * تحديث حالة اكتمال ملفات الأطباء
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfilesCompletionStatus()
    {
        try {
            $doctors = Doctor::with(['schedules'])->get();
            $updatedCount = 0;

            foreach ($doctors as $doctor) {
                $isComplete =
                    // التحقق من وجود صورة
                    !empty($doctor->image) &&
                    // التحقق من وجود تخصص
                    !empty($doctor->category_id) &&
                    // التحقق من وجود جدول مواعيد مع يوم واحد على الأقل متاح
                    $doctor->schedules->where('is_active', true)->count() > 0 &&
                    // التحقق من وجود رسوم استشارة
                    $doctor->consultation_fee > 0 &&
                    // التحقق من وجود وقت انتظار
                    $doctor->waiting_time > 0 &&
                    // التحقق من وجود عنوان كامل
                    !empty($doctor->address) && $doctor->governorate_id && $doctor->city_id;

                // تحديث حقل اكتمال الملف الشخصي إذا كان مختلفاً
                if ($doctor->is_profile_completed != $isComplete) {
                    $doctor->is_profile_completed = $isComplete;
                    $doctor->save();
                    $updatedCount++;
                }
            }

            return redirect()->route('doctors.incomplete_profiles')
                ->with('success', "تم تحديث حالة اكتمال بيانات {$updatedCount} طبيب بنجاح");
        } catch (\Exception $e) {
            \Log::error('Error updating doctors completion status: ' . $e->getMessage());
            return redirect()->route('doctors.incomplete_profiles')
                ->with('error', 'حدث خطأ أثناء تحديث حالة اكتمال البيانات: ' . $e->getMessage());
        }
    }
}
