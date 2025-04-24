<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
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

        // الإحصائيات المالية
        $stats = [
            'total_fees' => $query->sum('fees'),
            'paid_fees' => $query->where('is_paid', true)->sum('fees'),
            'unpaid_fees' => $query->where('is_paid', false)->sum('fees'),
            'total_appointments' => $query->count(),
        ];

        $appointments = $query->orderBy('scheduled_at', 'desc')->paginate(15);
        $doctors = Doctor::all();

        return view('admin::appointments.index', [
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

        return view('admin::appointments.create', [
            'doctors' => $doctors,
            'patients' => $patients,
            'title' => 'إضافة موعد جديد'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'scheduled_at' => 'required|date|after:now',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'fees' => 'nullable|numeric|min:0',
            'is_paid' => 'boolean',
            'is_important' => 'boolean'
        ]);

        $appointment = Appointment::create($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'تم إضافة الموعد بنجاح');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor', 'patient']);

        return view('admin::appointments.show', [
            'appointment' => $appointment,
            'title' => 'تفاصيل الموعد'
        ]);
    }

    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('admin::appointments.edit', [
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

        $appointment->update($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'تم تحديث الموعد بنجاح');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'تم حذف الموعد بنجاح');
    }
}
