<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Governorate;
use App\Models\City;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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
            'title' => 'My Profile',
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
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string|max:255',
        ], [
            'gender.required' => 'الجنس مطلوب',
            'gender.in' => 'قيمة الجنس غير صحيحة',
            'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة',
            'address.max' => 'العنوان لا يمكن أن يتجاوز 255 حرف',
        ]);

        // Update the patient profile
        $user->patient->update([
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
        ]);

        // Redirect with success message
        if (request()->has('redirect_to')) {
            return redirect(request('redirect_to'))->with('success', 'تم تحديث ملفك الطبي بنجاح');
        }

        return redirect()->route('profile')->with('success', 'تم تحديث ملفك الطبي بنجاح');
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
