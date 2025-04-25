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
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:yesterday',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:1000',
        ], [
            'doctor_id.required' => 'يرجى اختيار الطبيب',
            'appointment_date.required' => 'يرجى اختيار تاريخ الموعد',
            'appointment_date.after' => 'يجب أن يكون تاريخ الموعد في المستقبل',
            'appointment_time.required' => 'يرجى اختيار وقت الموعد',
        ]);

        // Convert date and time to datetime
        $scheduledAt = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Get doctor's consultation fee
        $doctor = Doctor::findOrFail($validated['doctor_id']);

        // Create the appointment
        $appointment = Appointment::create([
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => auth()->user()->patient->id,
            'scheduled_at' => $scheduledAt,
            'status' => 'scheduled',
            'notes' => $validated['notes'],
            'fees' => $doctor->price,
            'is_paid' => false
        ]);

        // Notify Admin about the new appointment
        User::role('Admin')->each(function($admin) use ($appointment) {
            $admin->notify(new NewAppointmentNotification($appointment));
        });

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'تم حجز الموعد بنجاح، سيتم التواصل معك قريباً للتأكيد');
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
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'fees' => 'nullable|numeric|min:0',
            'is_paid' => 'boolean',
            'is_important' => 'boolean'
        ]);

        $oldStatus = $appointment->status;
        $appointment->update($validated);

        // إرسال إشعارات عند تغيير حالة الموعد
        if ($oldStatus !== $validated['status']) {
            $notification = match($validated['status']) {
                'cancelled' => new AppointmentCancelledNotification($appointment),
                'completed' => new AppointmentCompletedNotification($appointment),
                default => null
            };

            if ($notification) {
                User::role('Admin')->each(function($admin) use ($notification) {
                    $admin->notify($notification);
                });
            }
        }

        return redirect()->route('appointments.index')
            ->with('success', 'تم تحديث الموعد بنجاح');
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
