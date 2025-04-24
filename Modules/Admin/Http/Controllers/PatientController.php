<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index()
    {
        $patients = User::where('type', 'patient')->paginate(10);
        return view('admin::patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin::patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:6|confirmed',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['type'] = 'patient';

        User::create($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'تم إضافة المريض بنجاح');
    }

    public function edit(User $patient)
    {
        return view('admin::patients.edit', compact('patient'));
    }

    public function update(Request $request, User $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $patient->update($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'تم تحديث بيانات المريض بنجاح');
    }

    public function destroy(User $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patients.index')
            ->with('success', 'تم حذف المريض بنجاح');
    }
}
