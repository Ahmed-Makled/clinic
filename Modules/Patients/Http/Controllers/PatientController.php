<?php

namespace Modules\Patients\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\Users\Entities\Users;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Notifications\NewPatientNotification;
use App\Notifications\PatientUpdatedNotification;
use App\Notifications\PatientDeletedNotification;

class PatientController extends Controller
{
    public function index()
    {
        $patients = User::role('Patient')->with('patient')->paginate(10);
        return view('patients::index', compact('patients'));
    }

    public function create()
    {
        return view('patients::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|min:6|confirmed',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['type'] = 'patient';

        // Create the user record
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => $validated['password'],
            'type' => $validated['type']
        ]);

        // Create the patient record
        $patient = Patient::create([
            'user_id' => $user->id,
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address']
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
        return view('patients::edit', compact('patient'));
    }

    public function update(Request $request, User $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->id,
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female'
        ]);

        // Update user record
        $patient->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number']
        ]);

        // Update patient record
        $patient->patient->update([
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address']
        ]);

        // Send notification to admins
        User::role('Admin')->each(function($admin) use ($patient) {
            $admin->notify(new PatientUpdatedNotification($patient->patient));
        });

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
}
