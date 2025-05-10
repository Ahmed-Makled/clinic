<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Users\Entities\User;
use Modules\Doctors\Entities\Doctor;
use Modules\Patients\Entities\Patient;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role_filter')) {
            $query->role($request->role_filter);
        }

        // Filter by status
        if ($request->filled('status_filter')) {
            $status = $request->status_filter === '1';
            $query->where('status', $status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('users::admin.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users::admin.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'role' => 'required|exists:roles,name',
            'status' => 'boolean'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone'],
            'status' => $validated['status'] ?? false
        ]);

        $user->assignRole($validated['role']);

        // Guardamos explícitamente la URL del listado de usuarios en lugar de la URL anterior
        // para asegurar que el redirect_back funcione correctamente
        $request->session()->put('redirect_back', route('users.index'));

        // التوجيه حسب نوع المستخدم
        if ($validated['role'] === 'Doctor') {
            // إنشاء سجل طبيب فارغ لهذا المستخدم
            $doctor = Doctor::create([
                'user_id' => $user->id,
            ]);

            // توجيه المستخدم لتعديل بيانات الطبيب مع إضافة معلمة redirect
            return redirect()->route('doctors.edit', [
                'doctor' => $doctor->id,
                'redirect' => 'users'
            ])->with('info', 'تم إضافة المستخدم بنجاح. يرجى استكمال بيانات الطبيب');
        } elseif ($validated['role'] === 'Patient') {
            // إنشاء سجل مريض فارغ لهذا المستخدم
            $patient = Patient::create([
                'user_id' => $user->id,
            ]);

            // توجيه المستخدم لتعديل بيانات المريض مع إضافة معلمة redirect
            return redirect()->route('patients.edit', [
                'patient' => $patient->id,
                'redirect' => 'users'
            ])->with('info', 'تم إضافة المستخدم بنجاح. يرجى استكمال بيانات المريض');
        }

        // إذا كان دوراً آخر، قم بالتوجيه للصفحة الرئيسية كالمعتاد
        return redirect()->route('users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function show(User $user)
    {
        return view('users::details', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users::admin.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'role' => 'required|exists:roles,name',
            'status' => 'boolean',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone'],
            'status' => $validated['status'] ?? false
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // إذا كان هناك تغيير في الدور
        $oldRoles = $user->getRoleNames();
        $newRole = $validated['role'];

        // تحديث الدور
        $user->syncRoles([$newRole]);

        // إذا تم تغيير الدور إلى Doctor وليس لديه سجل طبيب
        if ($newRole === 'Doctor' && !Doctor::where('user_id', $user->id)->exists() && !in_array('Doctor', $oldRoles->toArray())) {
            return redirect()->route('doctors.createFromUser', ['user' => $user->id])
                ->with('info', 'تم تحديث بيانات المستخدم بنجاح. الرجاء إضافة المعلومات الإضافية للطبيب');
        }

        // إذا تم تغيير الدور إلى Patient وليس لديه سجل مريض
        if ($newRole === 'Patient' && !Patient::where('user_id', $user->id)->exists() && !in_array('Patient', $oldRoles->toArray())) {
            return redirect()->route('patients.createFromUser', ['user' => $user->id])
                ->with('info', 'تم تحديث بيانات المستخدم بنجاح. الرجاء إضافة المعلومات الإضافية للمريض');
        }

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * Toggle user status (active/inactive)
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $user)
    {
        $user->status = !$user->status;
        $user->save();

        $statusMessage = $user->status ? 'تم تفعيل المستخدم بنجاح' : 'تم تعطيل المستخدم بنجاح';

        return redirect()->back()->with('success', $statusMessage);
    }
}
