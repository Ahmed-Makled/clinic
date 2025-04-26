<?php

namespace Modules\Appointments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\NewAppointmentNotification;
use App\Notifications\AppointmentCancelledNotification;
use App\Notifications\AppointmentCompletedNotification;
use App\Repositories\AppointmentRepository;
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
                case 'week':
                    $query->whereBetween('scheduled_at', [now(), now()->addWeek()]);
                    break;
                case 'custom':
                    if ($request->filled('start_date')) {
                        $query->whereDate('scheduled_at', '>=', $request->start_date);
                    }
                    if ($request->filled('end_date')) {
                        $query->whereDate('scheduled_at', '<=', $request->end_date);
                    }
                    break;
            }
        }

        // تصفية حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // تصفية حسب الطبيب
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // الإحصائيات المالية والعامة
        $stats = [
            'total_fees' => Appointment::sum('fees'),
            'paid_fees' => Appointment::where('is_paid', true)->sum('fees'),
            'unpaid_fees' => Appointment::where('is_paid', false)->sum('fees'),
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::whereDate('scheduled_at', Carbon::today())->count()
        ];

        $appointments = $query->orderBy('scheduled_at', 'desc')->paginate(15);
        $doctors = Doctor::all();

        return view('appointments::index', [
            'appointments' => $appointments,
            'doctors' => $doctors,
            'stats' => $stats,
            'date_filter' => $request->date_filter,
            'status_filter' => $request->status,
            'doctor_filter' => $request->doctor_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'title' => 'المواعيد'
        ]);
    }

    public function create()
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('appointments::create', [
            'doctors' => $doctors,
            'patients' => $patients,
            'title' => 'إضافة موعد جديد'
        ]);
    }

    public function store(Request $request)
    {
        // Log incoming request data for debugging
        Log::info('Appointment booking request:', $request->all());

        // Common validation rules
        $rules = [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'notes' => 'nullable|string|max:1000',
        ];

        // Add patient_id validation for admin creation
        if (auth()->user()->hasRole('Admin')) {
            $rules['patient_id'] = 'required|exists:patients,id';
        }

        $messages = [
            'doctor_id.required' => 'يرجى اختيار الطبيب',
            'doctor_id.exists' => 'الطبيب المختار غير موجود',
            'appointment_date.required' => 'يرجى اختيار تاريخ الموعد',
            'appointment_date.date' => 'تاريخ الموعد غير صالح',
            'appointment_date.after_or_equal' => 'يجب أن يكون تاريخ الموعد اليوم أو في المستقبل',
            'appointment_time.required' => 'يرجى اختيار وقت الموعد',
            'appointment_time.regex' => 'وقت الموعد غير صالح',
            'notes.max' => 'الملاحظات لا يمكن أن تتجاوز 1000 حرف'
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

            // Check if the time slot is available
            $doctor = Doctor::findOrFail($validated['doctor_id']);
            $conflictingAppointments = app(AppointmentRepository::class)->findConflictingAppointments(
                $doctor->id,
                $scheduledAt
            );

            if ($conflictingAppointments->isNotEmpty()) {
                return back()->withErrors(['appointment_time' => 'هذا الموعد محجوز بالفعل، يرجى اختيار وقت آخر'])->withInput();
            }

            // Set patient ID based on context
            $patientId = auth()->user()->hasRole('Admin')
                ? $validated['patient_id']
                : auth()->user()->patient->id;

            // Create the appointment
            $appointment = Appointment::create([
                'doctor_id' => $validated['doctor_id'],
                'patient_id' => $patientId,
                'scheduled_at' => $scheduledAt,
                'status' => 'scheduled',
                'notes' => $validated['notes'] ?? null,
                'fees' => $doctor->consultation_fee,
                'is_paid' => false,
                'is_important' => false
            ]);

            // Notify Admin about the new appointment
            User::role('Admin')->each(function($admin) use ($appointment) {
                $admin->notify(new NewAppointmentNotification($appointment));
            });

            // Log successful booking
            Log::info('Appointment booked successfully:', [
                'appointment_id' => $appointment->id,
                'doctor_id' => $doctor->id,
                'patient_id' => $patientId,
                'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s')
            ]);

            // Redirect based on context
            if (auth()->user()->hasRole('Admin')) {
                return redirect()->route('appointments.index')
                    ->with('success', 'تم إضافة الموعد بنجاح');
            }

            return redirect()->route('appointments.show', $appointment)
                ->with('success', 'تم حجز الموعد بنجاح، سيتم التواصل معك قريباً للتأكيد');

        } catch (\Exception $e) {
            Log::error('Appointment booking error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return back()->withErrors([
                'error' => 'حدث خطأ أثناء حجز الموعد. يرجى المحاولة مرة أخرى.'
            ])->withInput();
        }
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor', 'patient']);

        return view('appointments::show', [
            'appointment' => $appointment,
            'title' => 'تفاصيل الموعد'
        ]);
    }

    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('appointments::edit', [
            'appointment' => $appointment,
            'doctors' => $doctors,
            'patients' => $patients,
            'title' => 'تعديل الموعد'
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
            'is_paid' => 'boolean',
            'is_important' => 'boolean'
        ];

        $messages = [
            'doctor_id.required' => 'يرجى اختيار الطبيب',
            'doctor_id.exists' => 'الطبيب المختار غير موجود',
            'patient_id.required' => 'يرجى اختيار المريض',
            'patient_id.exists' => 'المريض المختار غير موجود',
            'appointment_date.required' => 'يرجى اختيار تاريخ الموعد',
            'appointment_date.date' => 'تاريخ الموعد غير صالح',
            'appointment_time.required' => 'يرجى اختيار وقت الموعد',
            'appointment_time.regex' => 'وقت الموعد غير صالح',
            'status.required' => 'يرجى اختيار حالة الموعد',
            'status.in' => 'حالة الموعد غير صالحة',
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
                $conflictingAppointments = app(AppointmentRepository::class)->findConflictingAppointments(
                    $validated['doctor_id'],
                    $scheduledAt,
                    30,
                    $appointment->id
                );

                if ($conflictingAppointments->isNotEmpty()) {
                    return back()->withErrors(['appointment_time' => 'هذا الموعد محجوز بالفعل، يرجى اختيار وقت آخر'])->withInput();
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
                'is_paid' => $validated['is_paid'] ?? false,
                'is_important' => $validated['is_important'] ?? false
            ]);

            // Send notifications if status changed
            if ($oldStatus !== $validated['status']) {
                $notification = match($validated['status']) {
                    'completed' => new AppointmentCompletedNotification($appointment),
                    'cancelled' => new AppointmentCancelledNotification($appointment),
                    default => null
                };

                if ($notification) {
                    $appointment->patient->user->notify($notification);
                }
            }

            return redirect()->route('appointments.index')
                ->with('success', 'تم تحديث الموعد بنجاح');

        } catch (\Exception $e) {
            Log::error('Error updating appointment:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث الموعد'])->withInput();
        }
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'تم حذف الموعد بنجاح');
    }

    /**
     * Show the appointment booking form.
     */
    public function book(Doctor $doctor)
    {
        // Get available time slots
        $timeSlots = get_appointment_time_slots(30, '09:00', '17:00');

        return view('appointments::book', compact('doctor', 'timeSlots'));
    }
}
