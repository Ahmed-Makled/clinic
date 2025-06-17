<?php

namespace Modules\Appointments\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Appointments\Entities\Appointment;
use Modules\Doctors\Entities\Doctor;
use Modules\Doctors\Entities\DoctorRating;
use Modules\Patients\Entities\Patient;
use Modules\Users\Entities\User;
use Modules\Appointments\Notifications\NewAppointmentNotification;
use Modules\Appointments\Notifications\AppointmentCancelledNotification;
use Modules\Appointments\Notifications\AppointmentCompletedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['doctor', 'patient']);

        // تصفية حسب التاريخ
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->today();
                    break;
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'past':
                    $query->where('scheduled_at', '<', Carbon::now());
                    break;
                case 'week':
                    $query->betweenDates(Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString());
                    break;
                case 'month':
                    $query->betweenDates(Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString());
                    break;
                case 'custom':
                    if ($request->filled(['start_date', 'end_date'])) {
                        $query->betweenDates($request->start_date, $request->end_date);
                    }
                    break;
            }
        }

        // تصفية حسب الحالة
        if ($request->filled('status_filter')) {
            // Map the status from the UI to the actual database values
            $statusMap = [
                'pending' => 'scheduled',
                'confirmed' => 'scheduled', // Currently both map to scheduled
                'completed' => 'completed',
                'cancelled' => 'cancelled'
            ];

            $status = $statusMap[$request->status_filter] ?? $request->status_filter;
            $query->where('status', $status);
        }

        // تصفية حسب الطبيب
        if ($request->filled('doctor_filter')) {
            $query->forDoctor($request->doctor_filter);
        }

        // البحث حسب اسم المريض
        if ($request->filled('search')) {
            $query->whereHas('patient.user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }

        // الإحصائيات المالية والعامة
        $stats = [
            'total_fees' => Appointment::sum('fees'),
            'paid_fees' => Appointment::whereHas('payment', function ($query) {
                $query->where('status', 'completed');
            })->sum('fees'),
            'unpaid_fees' => Appointment::whereDoesntHave('payment', function ($query) {
                $query->where('status', 'completed');
            })->sum('fees'),
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::today()->count(),
            // Add filter stats for debugging
            'current_filter_count' => $query->count()
        ];

        $appointments = $query->orderBy('created_at', 'desc')->paginate(15);
        $doctors = Doctor::all();

        // Status mapping for debugging
        $statusMap = [
            'pending' => 'scheduled',
            'confirmed' => 'scheduled',
            'completed' => 'completed',
            'cancelled' => 'cancelled'
        ];

        return view('appointments::admin.index', [
            'title' => 'إدارة المواعيد - Clinic Master',
            'appointments' => $appointments,
            'doctors' => $doctors,
            'stats' => $stats,
            'statusMap' => $statusMap,
            'request' => $request
        ]);
    }

    public function create()
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('appointments::admin.create', [
            'title' => 'إضافة حجز جديد - Clinic Master',
            'doctors' => $doctors,
            'patients' => $patients
        ]);
    }

    public function store(Request $request)
    {
        // التحقق من تسجيل دخول المستخدم
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول أولاً لتتمكن من حجز موعد');
        }

        $user = auth()->user();

        // إذا كان المستخدم مسؤولا، فيسمح له بإنشاء المواعيد
        if ($user->hasRole('Admin')) {
            // المسؤول يمكنه إنشاء المواعيد بدون قيود
        }
        // إذا كان المستخدم ليس مسؤولاً وليس مريضاً
        else if (!$user->isPatient()) {
            return back()
                ->with('error', 'عذراً، فقط المرضى أو المسؤولون يمكنهم حجز موعد');
        }
        // التحقق من وجود ملف المريض إذا كان المستخدم مريضاً عادياً
        else {
            $patient = $user->patient;
            if (!$patient) {
                // نحفظ البيانات في الجلسة لاستعادتها لاحقًا
                $request->flash();

                // نرجع إلى نفس الصفحة مع رسالة التحذير
                return back()
                    ->with('warning', 'يجب إكمال ملفك الشخصي كمريض أولاً لتتمكن من حجز موعد')
                    ->with('profile_required', true)
                    ->with('doctor_id', $request->input('doctor_id'));
            }
        }

        // Common validation rules
        $rules = [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'notes' => 'nullable|string|max:1000'
        ];

        // تحقق من معرف المريض
        // المسؤول يجب أن يحدد المريض من القائمة
        if ($user->hasRole('Admin')) {
            $rules['patient_id'] = 'required|exists:patients,id';
        }

        $messages = [
            'doctor_id.required' => 'يجب اختيار الطبيب',
            'doctor_id.exists' => 'الطبيب المحدد غير متوفر',
            'patient_id.required' => 'يجب اختيار المريض',
            'patient_id.exists' => 'المريض المحدد غير متوفر',
            'appointment_date.required' => 'يجب اختيار تاريخ الحجز',
            'appointment_date.date' => 'صيغة التاريخ غير صحيحة',
            'appointment_date.after_or_equal' => 'لا يمكن حجز موعد في تاريخ سابق',
            'appointment_time.required' => 'يجب اختيار وقت الحجز',
            'appointment_time.regex' => 'صيغة الوقت غير صحيحة',
            'notes.max' => 'الملاحظات لا يمكن أن تتجاوز 1000 حرف'
        ];

        try {

            // التحقق من وجود معرف المريض إذا كان المستخدم مسؤولاً
            if ($user->hasRole('Admin') && !$request->has('patient_id')) {
                return back()
                    ->withErrors(['patient_id' => 'يجب اختيار المريض'])
                    ->withInput()
                    ->with('error', 'يرجى اختيار المريض');
            }

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'يرجى تصحيح الأخطاء التالية:');
            }

            $validated = $validator->validated();

            // Convert date and time to datetime
            try {
                $scheduledAt = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $validated['appointment_date'] . ' ' . $validated['appointment_time']
                );
            } catch (\Exception $e) {
                Log::error('Date parsing error:', [
                    'date' => $validated['appointment_date'],
                    'time' => $validated['appointment_time'],
                    'error' => $e->getMessage()
                ]);
                return back()->withErrors(['appointment_date' => 'صيغة التاريخ أو الوقت غير صحيحة'])->withInput();
            }

            // Check if the time slot is available
            $doctor = Doctor::findOrFail($validated['doctor_id']);

            // تحديد الحجوزات المتاحة لهذا التاريخ
            $availableSlots = $doctor->getAvailableSlots($validated['appointment_date']);

            // التحقق من أن الوقت المطلوب ما زال متاحًا
            if (!in_array($validated['appointment_time'], $availableSlots)) {
                return back()->withErrors([
                    'appointment_time' => 'عذراً، هذا الحجز غير متاح للحجز. يرجى اختيار وقت آخر من القائمة.'
                ])->withInput();
            }

            // تحديد معرف المريض
            $patientId = null;
            if ($user->hasRole('Admin')) {
                // للمسؤول، استخدم معرف المريض من النموذج
                if (isset($validated['patient_id'])) {
                    $patientId = $validated['patient_id'];
                } else {
                    // خطأ: لم يتم توفير معرف المريض
                    return back()
                        ->withErrors(['patient_id' => 'يجب اختيار المريض'])
                        ->withInput()
                        ->with('error', 'يرجى اختيار المريض');
                }
            } else {
                // للمريض، استخدم معرف المريض المرتبط بالمستخدم
                $patientId = $user->patient->id;
            }

            // إنشاء الموعد
            $appointment = Appointment::create([
                'doctor_id' => $validated['doctor_id'],
                'patient_id' => $patientId,
                'scheduled_at' => $scheduledAt,
                'status' => 'scheduled',
                'notes' => $validated['notes'] ?? null,
                'fees' => $doctor->consultation_fee,
                'is_important' => false
            ]);


            // Notify relevant parties
            $doctor->user->notify(new NewAppointmentNotification($appointment));

            // Notify Admin about the new appointment
            User::role('Admin')->each(function ($admin) use ($appointment) {
                $admin->notify(new NewAppointmentNotification($appointment));
            });

            // Log successful booking
            Log::info('Appointment booked successfully:', [
                'appointment_id' => $appointment->id,
                'doctor_id' => $doctor->id,
                'patient_id' => $user->hasRole('Admin') ? $validated['patient_id'] : $user->patient->id,
                'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s')
            ]);

            // تحسين رسالة النجاح بمزيد من التفاصيل
            $arabicDate = $scheduledAt->locale('ar')->translatedFormat('l d F Y');
            $arabicTime = $scheduledAt->format('h:i A');

            $successMessage = 'تم تأكيد حجز موعدك بنجاح! ';
            $successMessage .= 'حجزك مع د. ' . $doctor->name . ' يوم ' . $arabicDate . ' الساعة ' . $arabicTime . '. ';
            $successMessage .= 'يرجى الوصول قبل الحجز بـ 15 دقيقة. سيتم إرسال تفاصيل إضافية إلى بريدك الإلكتروني.';

            return redirect()->route('appointments.show', $appointment)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Appointment booking error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return back()->withErrors([
                'error' => 'عذراً، حدث خطأ أثناء حجز الحجز. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.'
            ])->withInput();
        }
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor', 'patient']);

        // تحقق من وجود تقييم سابق للطبيب في هذا الحجز
        $existingRating = DoctorRating::where('appointment_id', $appointment->id)
            ->where('patient_id', $appointment->patient_id)
            ->first();

        // استخدام قالب مختلف حسب نوع المستخدم
        if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Doctor')) {
            return view('appointments::admin.details', [
                'title' => 'تفاصيل الحجز - Clinic Master',
                'appointment' => $appointment,
                'existingRating' => $existingRating
            ]);
        } else {
            return view('appointments::show', [
                'title' => 'تفاصيل الموعد - Clinic Master',
                'appointment' => $appointment,
                'existingRating' => $existingRating
            ]);
        }
    }

    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('appointments::admin.edit', [
            'title' => 'تعديل الحجز - Clinic Master',
            'appointment' => $appointment,
            'doctors' => $doctors,
            'patients' => $patients
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        // Common validation rules
        $rules = [
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            // is_paid field removed as payment status is managed by Stripe
            'is_important' => 'boolean'
        ];

        $messages = [
            'doctor_id.required' => 'يرجى اختيار الطبيب',
            'doctor_id.exists' => 'الطبيب المختار غير موجود',
            'patient_id.required' => 'يرجى اختيار المريض',
            'patient_id.exists' => 'المريض المختار غير موجود',
            'appointment_date.required' => 'يرجى اختيار تاريخ الحجز',
            'appointment_date.date' => 'تاريخ الحجز غير صالح',
            'appointment_time.required' => 'يرجى اختيار وقت الحجز',
            'appointment_time.regex' => 'وقت الحجز غير صالح',
            'status.required' => 'يرجى اختيار حالة الحجز',
            'status.in' => 'حالة الحجز غير صالحة',
            'notes.max' => 'الملاحظات لا يمكن أن تتجاوز 1000 حرف',
        ];

        try {
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Log::error('Validation failed:', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            // Convert date and time to datetime
            try {
                $scheduledAt = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $validated['appointment_date'] . ' ' . $validated['appointment_time']
                );
            } catch (\Exception $e) {
                Log::error('Date parsing error:', [
                    'date' => $validated['appointment_date'],
                    'time' => $validated['appointment_time'],
                    'error' => $e->getMessage()
                ]);
                return back()->withErrors(['appointment_date' => 'تاريخ أو وقت غير صالح'])->withInput();
            }

            // Check for conflicting appointments if date/time changed
            if ($scheduledAt->format('Y-m-d H:i') !== $appointment->scheduled_at->format('Y-m-d H:i')) {
                $conflictingAppointments = Appointment::hasConflict(
                    $validated['doctor_id'],
                    $scheduledAt,
                    30,
                    $appointment->id
                )->get();

                if ($conflictingAppointments->isNotEmpty()) {
                    return back()->withErrors(['appointment_time' => 'هذا الحجز محجوز بالفعل، يرجى اختيار وقت آخر'])->withInput();
                }
            }

            // Update the appointment
            $oldStatus = $appointment->status;

            $appointment->update([
                'doctor_id' => $validated['doctor_id'],
                'patient_id' => $validated['patient_id'],
                'scheduled_at' => $scheduledAt,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                // is_paid field removed as payment status is managed by Stripe
                'is_important' => $validated['is_important'] ?? false
            ]);

            // Send notifications if status changed
            if ($oldStatus !== $validated['status']) {
                $notification = match ($validated['status']) {
                    'completed' => new AppointmentCompletedNotification($appointment),
                    'cancelled' => new AppointmentCancelledNotification($appointment),
                    default => null
                };

                if ($notification) {
                    $appointment->patient->user->notify($notification);
                }
            }

            return redirect()->route('appointments.index')
                ->with('success', 'تم تحديث الحجز بنجاح');

        } catch (\Exception $e) {
            Log::error('Error updating appointment:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث الحجز'])->withInput();
        }
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'تم حذف الحجز بنجاح');
    }

    /**
     * Mark an appointment as completed.
     */
    public function complete(Appointment $appointment)
    {
        try {
            // التحقق من أن موعد الحجز قد حان أو مر
            $now = Carbon::now();
            $appointmentTime = $appointment->scheduled_at;

            if ($now->isBefore($appointmentTime)) {
                $timeRemaining = $appointmentTime->diffForHumans($now);
                return back()->withErrors([
                    'error' => "لا يمكن إتمام الحجز قبل موعد الحجز المحدد. الموعد {$timeRemaining}"
                ]);
            }

            $oldStatus = $appointment->status;
            $appointment->update([
                'status' => 'completed'
            ]);

            if ($oldStatus !== 'completed') {
                $appointment->patient->user->notify(new AppointmentCompletedNotification($appointment));
            }

            return redirect()->back()->with('success', 'تم إتمام الحجز بنجاح');
        } catch (\Exception $e) {
            Log::error('Error completing appointment:', [
                'error' => $e->getMessage(),
                'appointment_id' => $appointment->id
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء إتمام الحجز']);
        }
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Appointment $appointment)
    {
        try {
            // سجل محاولة الإلغاء
            Log::info('Attempting to cancel appointment:', [
                'appointment_id' => $appointment->id,
                'current_status' => $appointment->status,
                'user_id' => auth()->id(),
                'user_type' => auth()->user()->roles->pluck('name')
            ]);

            $oldStatus = $appointment->status;
            $appointment->update([
                'status' => 'cancelled'
            ]);

            // سجل نجاح تحديث الحالة
            Log::info('Appointment status updated:', [
                'appointment_id' => $appointment->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled'
            ]);

            if ($oldStatus !== 'cancelled') {
                $appointment->patient->user->notify(new AppointmentCancelledNotification($appointment));

                // سجل إرسال الإشعار
                Log::info('Cancellation notification sent to patient', [
                    'patient_id' => $appointment->patient->id,
                    'patient_email' => $appointment->patient->user->email
                ]);
            }

            // تحقق من نوع المستخدم وتوجيهه بشكل مناسب
            $user = auth()->user();

            // إعداد الرسائل المناسبة حسب نوع المستخدم
            if ($user->hasRole('Admin') || $user->hasRole('Doctor')) {
                // للمشرفين والأطباء
                $doctor = $appointment->doctor;
                $patient = $appointment->patient;
                $successMessage = "تم إلغاء حجز المريض {$patient->name} مع الدكتور {$doctor->name} بنجاح";

                Log::info('Admin/Doctor redirect: back with success message');
                return redirect()->back()->with('success', $successMessage);
            } else {
                // للمرضى
                $doctor = $appointment->doctor;
                $appointmentDate = $appointment->scheduled_at->locale('ar')->translatedFormat('l d F Y');
                $appointmentTime = $appointment->scheduled_at->format('h:i A');

                $successMessage = "تم إلغاء حجزك مع د. {$doctor->name} بتاريخ {$appointmentDate} الساعة {$appointmentTime} بنجاح";
                $successMessage .= ".<br/>يمكنك حجز موعد جديد في أي وقت من صفحة الأطباء.";

                Log::info('Patient redirect: to appointment details with success message');
                return redirect()->route('appointments.show', $appointment)
                    ->with('success', $successMessage);
            }
        } catch (\Exception $e) {
            // سجل الخطأ بتفاصيل أكثر
            Log::error('Error cancelling appointment:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'appointment_id' => $appointment->id
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء إلغاء الحجز: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the appointment booking form.
     */
    public function book(Doctor $doctor)
    {
        // التحقق من تسجيل دخول المستخدم
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول أولاً لتتمكن من حجز موعد');
        }

        $user = auth()->user();

        // إذا كان المستخدم ليس آدمن وليس مريض
        if (!$user->isPatient()) {
            return back()
                ->with('error', 'عذراً، فقط المرضى يمكنهم حجز موعد');
        }

        // Get selected date or default to today
        $selectedDate = request('date') ?? now()->format('Y-m-d');
        $selectedDateObj = Carbon::parse($selectedDate);

        // Get the day name in English lowercase
        $dayName = strtolower($selectedDateObj->format('l'));

        // Get all schedules for mapping days
        $schedules = $doctor->schedules;

        // Arabic day names mapping
        $days = [
            'sunday' => 'الأحد',
            'monday' => 'الإثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت'
        ];

        // Find schedule for this day
        $schedule = $schedules->where('day', $dayName)->where('is_active', true)->first();

        // If no schedule exists for this day
        if (!$schedule) {
            $availableSlots = [];
            session()->flash('schedule_error', 'لا توجد مواعيد متاحة في هذا اليوم.');
            return view('appointments::book', [
                'title' => 'حجز موعد - Clinic Master',
                'doctor' => $doctor,
                'availableSlots' => $availableSlots,
                'selectedDate' => $selectedDate,
                'schedules' => $schedules,
                'days' => $days
            ]);
        }

        if (!$schedule->slot_duration) {
            $schedule->slot_duration = 30; // Default to 30 minutes if not set
        }

        // Get available slots for this schedule
        $availableSlots = $doctor->getAvailableSlots($selectedDate);

        return view('appointments::book', [
            'title' => 'حجز موعد - Clinic Master',
            'doctor' => $doctor,
            'availableSlots' => $availableSlots,
            'selectedDate' => $selectedDate,
            'schedules' => $schedules,
            'days' => $days
        ]);
    }



    /**
     * Get available time slots for a specific doctor and date.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $date = $request->date;

        // Get available slots for the doctor on the specified date
        $availableSlots = $doctor->getAvailableSlots($date);

        return response()->json([
            'success' => true,
            'slots' => $availableSlots,
        ]);
    }

    /**
     * الحصول على الأيام المتاحة للطبيب
     */
    public function getDoctorAvailableDays(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        // الحصول على أيام العمل من جدول الطبيب
        $availableDays = $doctor->schedules()
            ->where('is_active', true)
            ->pluck('day')
            ->toArray();

        return response()->json([
            'success' => true,
            'availableDays' => $availableDays,
        ]);
    }
}
