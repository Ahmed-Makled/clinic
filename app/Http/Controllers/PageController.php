<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Governorate;
use App\Models\City;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PageController extends Controller
{
    public function home()
    {
        return view('home', [
            'title' => 'Clinic Master',
            'classes' => 'bg-white',
            'categories' => Category::where('status', 1 )->get(),
            'governorates' => Governorate::with('cities')->get(),
            'cities' => City::all(),
            'doctors' => Doctor::where('status', true)->get(),
        ]);
    }

    public function about()
    {
        return view('about', [
            'title' => 'About Us',
            'classes' => 'bg-white'
        ]);
    }

    public function profile()
    {
        return view('profile', [
            'title' => 'الصفحة الشخصية',
            'classes' => 'bg-white',
            'user' => auth()->user()
        ]);
    }

    /**
     * Store patient profile information
     */
    public function storeProfile(Request $request)
    {
        // Verify that the user is a patient
        $user = auth()->user();

        if (!$user->isPatient()) {
            return back()->with('error', 'لا يمكن إنشاء ملف طبي إلا للمرضى');
        }

        // Verify that the patient doesn't already have a profile
        if ($user->patient) {
            return back()->with('error', 'لديك بالفعل ملف طبي');
        }

        // Validate inputs
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

        // Create the patient profile
        $patient = \App\Models\Patient::create([
            'user_id' => $user->id,
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
        ]);

        // Redirect with success message
        if ($request->has('redirect_to')) {
            return redirect($request->input('redirect_to'))->with('success', 'تم إنشاء ملفك الطبي بنجاح');
        }

        return redirect()->route('profile')->with('success', 'تم إنشاء ملفك الطبي بنجاح');
    }

    /**
     * Update patient profile information
     */
    public function updateProfile(Request $request)
    {
        // Verify that the user is a patient
        $user = auth()->user();

        if (!$user->isPatient() || !$user->patient) {
            return back()->with('error', 'لا يوجد ملف طبي للتعديل');
        }

        // Validate inputs
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

        // Update user basic information
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number']
        ]);

        // Update the patient profile
        $user->patient->update([
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
        ]);

        // Redirect with success message
        if (request()->has('redirect_to')) {
            return redirect(request('redirect_to'))->with('success', 'تم تحديث الملف الشخصي بنجاح');
        }

        return redirect()->route('profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        // Validate inputs
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

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    public function getCities(Governorate $governorate)
    {
        $cities = $governorate->cities->map(function($city) {
            return [
                'id' => $city->id,
                'name' => $city->name
            ];
        });

        return response()->json($cities);
    }

    public function search(Request $request)
    {
        $query = Doctor::query()
            ->where('status', true)
            ->with(['categories', 'governorate', 'city']);

        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('doctors')) {
            $query->where('id', $request->doctors);
        }

        $doctors = $query->latest()->paginate(12);

        return view('doctors::search', [
            'title' => 'نتائج البحث',
            'classes' => 'bg-white',
            'doctors' => $doctors,
            'categories' => Category::where('status', 1)->get(),
            'governorates' => Governorate::with('cities')->get()
        ]);
    }
}
