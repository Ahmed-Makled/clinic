<?php

namespace Modules\Patients\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Patients\Entities\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PatientProfileController extends Controller
{
    /**
     * عرض الصفحة الشخصية للمريض
     */
    public function profile()
    {
        return view('patients::profile', [
            'title' => 'الصفحة الشخصية',
            'classes' => 'bg-white',
            'user' => auth()->user()
        ]);
    }

    /**
     * إنشاء ملف المريض الطبي
     */
    public function storeProfile(Request $request)
    {
        // التحقق من أن المستخدم هو مريض
        $user = auth()->user();

        if (!$user->isPatient()) {
            return back()->with('error', 'لا يمكن إنشاء ملف طبي إلا للمرضى');
        }

        // التحقق من أن المريض ليس لديه ملف طبي بالفعل
        if ($user->patient) {
            return back()->with('error', 'لديك بالفعل ملف طبي');
        }

        // التحقق من المدخلات
        $validated = $request->validate([
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string|max:255',
        ], [
            'gender.required' => 'الجنس مطلوب',
            'gender.in' => 'قيمة الجنس غير صحيحة',
            'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة',
            'address.max' => 'العنوان لا يمكن أن يتجاوز 255 حرف',
        ]);

        // إنشاء ملف المريض
        $patient = Patient::create([
            'user_id' => $user->id,
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
        ]);

        // إعادة التوجيه مع رسالة نجاح
        if ($request->has('redirect_to')) {
            return redirect($request->input('redirect_to'))->with('success', 'تم إنشاء ملفك الطبي بنجاح');
        }

        return redirect()->route('profile')->with('success', 'تم إنشاء ملفك الطبي بنجاح');
    }

    /**
     * تحديث بيانات المريض
     */
    public function updateProfile(Request $request)
    {
        // التحقق من أن المستخدم هو مريض
        $user = auth()->user();

        if (!$user->isPatient() || !$user->patient) {
            return back()->with('error', 'لا يوجد ملف طبي للتعديل');
        }

        // التحقق من المدخلات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'gender.required' => 'الجنس مطلوب',
            'gender.in' => 'قيمة الجنس غير صحيحة',
            'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة',
            'address.max' => 'العنوان لا يمكن أن يتجاوز 255 حرف',
        ]);

        // تحديث معلومات المستخدم الأساسية
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number']
        ]);

        // تحديث ملف المريض
        $user->patient->update([
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
        ]);

        // إعادة التوجيه مع رسالة نجاح
        if (request()->has('redirect_to')) {
            return redirect(request('redirect_to'))->with('success', 'تم تحديث الملف الشخصي بنجاح');
        }

        return redirect()->route('profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * تحديث كلمة مرور المستخدم
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        // التحقق من المدخلات
        $validated = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('كلمة المرور الحالية غير صحيحة');
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
            ],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.confirmed' => 'كلمة المرور الجديدة غير متطابقة مع التأكيد',
        ]);

        // تحديث كلمة المرور
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}
