<?php

namespace Modules\Patients\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Users\Entities\User;
use Modules\Users\Entities\Users;
use Modules\Patients\Entities\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Modules\Patients\Notifications\NewPatientNotification;
use Modules\Patients\Notifications\PatientUpdatedNotification;
use Modules\Patients\Notifications\PatientDeletedNotification;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('Patient')
            ->with('patient')
            ->select('users.*')
            ->withCount('appointments');  // Changed this line to just count appointments

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('users.name', 'like', '%' . $request->search . '%')
                  ->orWhere('users.email', 'like', '%' . $request->search . '%')
                  ->orWhere('users.phone_number', 'like', '%' . $request->search . '%');
            });
        }

        // Gender filter
        if ($request->filled('gender_filter')) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('patients.gender', $request->gender_filter);
            });
        }

        // Status filter
        if ($request->filled('status_filter')) {
            $status = $request->status_filter === '1';
            $query->where('users.status', $status);
        }

        // Default ordering
        $query->latest();

        $patients = $query->paginate(10)->withQueryString();
        return view('patients::admin.index', [
            'title' => 'إدارة المرضى - Clinic Master',
            'patients' => $patients
        ]);
    }

    public function create()
    {
        return view('patients::admin.create', [
            'title' => 'إضافة مريض جديد - Clinic Master'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required',  // Removed string|max:20 validation
            'password' => 'required|min:6|confirmed',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            // Campos adicionales
            'medical_history' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'allergies' => 'nullable|string'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['type'] = 'patient';

        // Create the user record
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => $validated['password'],
            'type' => $validated['type'],
            'status' => true // Set default status to active
        ]);

        // Create the patient record
        $patient = Patient::create([
            'user_id' => $user->id,
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            // Añadir campos adicionales
            'medical_history' => $validated['medical_history'],
            'emergency_contact' => $validated['emergency_contact'],
            'blood_type' => $validated['blood_type'],
            'allergies' => $validated['allergies']
        ]);

        // Assign patient role with web guard
        $role = Role::findByName('Patient', 'web');
        $user->assignRole($role);

        // Send notification to admins
        User::role('Admin')->each(function($admin) use ($patient) {
            $admin->notify(new NewPatientNotification($patient));
        });

        return redirect()->route('patients.index')
            ->with('success', 'تم إضافة المريض بنجاح');
    }

    public function edit(User $patient)
    {
        $patient->load('patient');
        return view('patients::admin.edit', [
            'title' => 'تعديل بيانات المريض - Clinic Master',
            'patient' => $patient
        ]);
    }

    public function update(Request $request, User $patient)
    {
        $patient->load('patient');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->id,
            'phone_number' => 'required', // Removed string|max:20 validation
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            // Campos adicionales
            'medical_history' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'allergies' => 'nullable|string'
        ]);

        // Handle status toggle - convert checkbox value to boolean
        $status = $request->has('status');

        // Update user record
        $patient->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'status' => $status
        ]);

        // Update patient record
        $patient->patient->update([
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            // Actualizar campos adicionales
            'medical_history' => $validated['medical_history'],
            'emergency_contact' => $validated['emergency_contact'],
            'blood_type' => $validated['blood_type'],
            'allergies' => $validated['allergies']
        ]);

        // Send notification to admins
        User::role('Admin')->each(function($admin) use ($patient) {
            $admin->notify(new PatientUpdatedNotification($patient->patient));
        });

        // التحقق من وجود معلمة redirect في URL
        if ($request->has('redirect') && $request->redirect === 'users') {
            return redirect()->route('users.index')
                ->with('success', 'تم تحديث بيانات المريض بنجاح');
        }

        // أو التحقق من وجود URL مصدر في الجلسة
        if ($request->session()->has('redirect_back')) {
            $redirectBack = $request->session()->get('redirect_back');
            $request->session()->forget('redirect_back');
            return redirect()->to($redirectBack)
                ->with('success', 'تم تحديث بيانات المريض بنجاح');
        }

        return redirect()->route('patients.index')
            ->with('success', 'تم تحديث بيانات المريض بنجاح');
    }

    public function destroy(User $patient)
    {
        $patientName = $patient->name;
        $patient->delete();

        // Send notification to admins
        User::role('Admin')->each(function($admin) use ($patientName) {
            $admin->notify(new PatientDeletedNotification($patientName));
        });

        return redirect()->route('patients.index')
            ->with('success', 'تم حذف المريض بنجاح');
    }

    public function details(User $patient)
    {
        $patient->load('patient');
        return view('patients::admin.details', [
            'title' => 'تفاصيل المريض - Clinic Master',
            'patient' => $patient
        ]);
    }
}
