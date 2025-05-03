<?php

namespace Modules\Doctors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Category;
use App\Models\Governorate;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Notifications\NewDoctorNotification;
use App\Notifications\DoctorUpdatedNotification;
use App\Notifications\DoctorDeletedNotification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DoctorsController extends Controller
{


    protected function applyFilters($query, Request $request)
    {
        // تطبيق البحث
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('bio', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // تطبيق فلتر التخصص
        if ($request->filled('category') || $request->filled('category_filter')) {
            $categoryId = $request->category ?? $request->category_filter;
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
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

        return view('doctors::index', compact('doctors', 'categories', 'governorates'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $governorates = Governorate::with('cities')->get();
        return view('doctors::create', compact('categories', 'governorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'string', 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:8'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'waiting_time' => ['nullable', 'integer', 'min:0'],
            'title' => ['required', 'string', 'max:100'],
            'specialization' => ['required', 'string', 'max:100'],
            'categories' => ['required', 'array', 'exists:categories,id'],
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
            ]
        ], [
            'name.required' => 'اسم الطبيب مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'consultation_fee.required' => 'سعر الكشف مطلوب',
            'consultation_fee.numeric' => 'سعر الكشف يجب أن يكون رقماً',
            'consultation_fee.min' => 'سعر الكشف يجب أن يكون أكبر من صفر',
            'waiting_time.integer' => 'مدة الانتظار يجب أن تكون رقماً صحيحاً',
            'waiting_time.min' => 'مدة الانتظار يجب أن تكون أكبر من صفر',
            'title.required' => 'المسمى الوظيفي مطلوب',
            'specialization.required' => 'التخصص مطلوب',
            'categories.required' => 'التخصصات مطلوبة',
            'categories.exists' => 'التخصص المختار غير موجود',
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
            'schedules.required' => 'جدول المواعيد مطلوب',
            'schedules.*.start_time.required_with' => 'يجب تحديد وقت البداية عند اختيار اليوم',
            'schedules.*.start_time.date_format' => 'صيغة وقت البداية غير صحيحة',
            'schedules.*.end_time.required_with' => 'يجب تحديد وقت النهاية عند اختيار اليوم',
            'schedules.*.end_time.date_format' => 'صيغة وقت النهاية غير صحيحة',
            'schedules.*.end_time.after' => 'يجب أن يكون وقت النهاية بعد وقت البداية'
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone_number' => $validated['phone'],
                'type' => 'doctor',
                'status' => true
            ]);

            $user->assignRole('Doctor');

            // Create doctor
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'title' => $validated['title'],
                'specialization' => $validated['specialization'],
                'description' => $validated['description'],
                'consultation_fee' => $validated['consultation_fee'],
                'waiting_time' => $validated['waiting_time'],
                'experience_years' => $validated['experience_years'],
                'gender' => $validated['gender'],
                'status' => true,
                'address' => $validated['address'],
                'governorate_id' => $validated['governorate_id'],
                'city_id' => $validated['city_id']
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $doctor->image = Doctor::uploadImage($request->file('image'));
                $doctor->save();
            }

            // Link categories
            $doctor->categories()->sync($validated['categories']);

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

            DB::commit();
            return redirect()->route('doctors.index')->with('success', 'تم إضافة الطبيب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating doctor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة الطبيب. ' . $e->getMessage());
        }
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load(['schedules']); // تحميل جداول المواعيد
        $categories = Category::all();
        $governorates = Governorate::with('cities')->get();
        return view('doctors::edit', compact('doctor', 'categories', 'governorates'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'specialization' => ['required', 'string', 'max:100'],
            'categories' => ['required', 'array', 'exists:categories,id'],
            'gender' => ['required', Rule::in(['ذكر', 'انثي'])],
            'experience_years' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'status' => ['nullable', 'boolean'],
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
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $doctor->deleteImage();
                $doctor->image = Doctor::uploadImage($request->file('image'));
            }

            $doctor->update([
                'title' => $validated['title'],
                'specialization' => $validated['specialization'],
                'experience_years' => $validated['experience_years'],
                'description' => $validated['description'],
                'consultation_fee' => $validated['consultation_fee'],
                'waiting_time' => $validated['waiting_time'] ?? 30,
                'gender' => $validated['gender'],
                'status' => $request->boolean('status'),
                'address' => $validated['address'],
                'governorate_id' => $validated['governorate_id'],
                'city_id' => $validated['city_id']
            ]);

            // Sync categories
            $doctor->categories()->sync($request->categories);

            // Update schedules
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

            return redirect()->route('doctors.index')
                ->with('success', 'تم تحديث بيانات الطبيب بنجاح');

        } catch (\Exception $e) {
            \Log::error('Error updating doctor: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات الطبيب');
        }
    }

    public function destroy(Doctor $doctor)
    {
        $doctorName = $doctor->name;
        $doctor->categories()->detach();

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
            'categories',
            'user',
            'governorate',
            'city',
            'schedules' // إضافة تحميل جداول المواعيد
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
        $averageConsultationFee = Doctor::whereHas('categories', function ($query) use ($doctor) {
            $query->whereIn('categories.id', $doctor->categories->pluck('id'));
        })
            ->where('id', '!=', $doctor->id)
            ->avg('consultation_fee') ?? 0;

        $feeComparisonRate = $averageConsultationFee > 0
            ? (($doctor->consultation_fee - $averageConsultationFee) / $averageConsultationFee) * 100
            : 0;

        // حساب إجمالي الإيرادات وتحليلها
        $totalEarnings = $doctor->appointments()
            ->where('status', 'completed')
            ->where('is_paid', true)
            ->sum('fees');

        $lastMonthEarnings = $doctor->appointments()
            ->whereMonth('scheduled_at', $lastMonth)
            ->where('status', 'completed')
            ->where('is_paid', true)
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

        return view('doctors::details', compact(
            'doctor',
            'appointments',
            'completedAppointments',
            'cancelledAppointments',
            'completedGrowthRate',
            'feeComparisonRate',
            'totalEarnings',
            'earningsGrowthRate'
        ));
    }

    /**
     * Show form for creating doctor details from existing user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function createFromUser(Request $request)
    {
        // التحقق من وجود المستخدم
        $user = User::findOrFail($request->user);

        // التحقق من أن المستخدم له دور Doctor
        if (!$user->hasRole('Doctor')) {
            return redirect()->route('users.index')
                ->with('error', 'المستخدم المحدد لا يملك دور طبيب');
        }

        // التحقق من أن المستخدم ليس لديه سجل طبيب بالفعل
        $existingDoctor = Doctor::where('user_id', $user->id)->first();
        if ($existingDoctor) {
            return redirect()->route('doctors.edit', $existingDoctor->id)
                ->with('info', 'هذا المستخدم لديه بيانات طبيب بالفعل، يمكنك تعديل البيانات');
        }

        $categories = Category::all();
        $governorates = Governorate::with('cities')->get();

        return view('doctors::create_from_user', compact('user', 'categories', 'governorates'));
    }

    /**
     * Store doctor details for existing user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFromUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'consultation_fee' => 'required|numeric|min:0',
            'waiting_time' => 'nullable|integer|min:0',
            'categories' => 'required|exists:categories,id',
            'gender' => 'required|in:ذكر,انثي',
            'experience_years' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'address' => 'nullable|string',
            'governorate_id' => 'required|exists:governorates,id',
            'city_id' => 'required|exists:cities,id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Create doctor record with user_id
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => $validated['bio'] ?? null,
            'description' => $validated['description'] ?? null,
            'consultation_fee' => $validated['consultation_fee'],
            'waiting_time' => $validated['waiting_time'],
            'experience_years' => $validated['experience_years'] ?? null,
            'gender' => $validated['gender'],
            'status' => true,
            'address' => $validated['address'] ?? null,
            'governorate_id' => $validated['governorate_id'],
            'city_id' => $validated['city_id']
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $doctor->image = Doctor::uploadImage($request->file('image'));
            $doctor->save();
        }

        // Sync categories
        $doctor->categories()->sync($request->categories);

        // Send notification to admins
        User::role('Admin')->each(function ($admin) use ($doctor) {
            $admin->notify(new NewDoctorNotification($doctor));
        });

        return redirect()->route('doctors.index')
            ->with('success', 'تم إضافة بيانات الطبيب بنجاح');
    }

    public function search(Request $request)
    {
        $query = Doctor::with(['categories', 'governorate', 'city'])
            ->whereHas('user', function ($q) {
                $q->where('status', true);
            });

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
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

        return view('doctors::search', compact('doctors', 'categories', 'governorates'));
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
        $stats['averageConsultationFee'] = Doctor::whereHas('categories', function ($query) use ($doctor) {
            $query->whereIn('categories.id', $doctor->categories->pluck('id'));
        })
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
}
