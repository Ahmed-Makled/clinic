<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'patient'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);

        return view('admin::appointments.index', [
            'appointments' => $appointments,
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
            'notes' => 'nullable|string|max:1000'
        ]);

        Appointment::create($validated);

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
            'notes' => 'nullable|string|max:1000'
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
