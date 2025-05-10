<?php

namespace App\Http\Controllers;

use Modules\Users\Entities\Governorate;
use Modules\Specialties\Entities\Category;

use Modules\Users\Entities\City;

use Modules\Doctors\Entities\Doctor;
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
            'doctors' => Doctor::where('status', true)
                         ->where('is_profile_completed', true)
                         ->get(),
        ]);
    }

    public function about()
    {
        return view('about', [
            'title' => 'About Us',
            'classes' => 'bg-white'
        ]);
    }

    /**
     * تم نقل الدوال التالية إلى Modules\Patients\Http\Controllers\PatientProfileController:
     * - profile()
     * - storeProfile()
     * - updateProfile()
     * - updatePassword()
     */

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
            ->where('is_profile_completed', true) // Only show doctors with complete profiles
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
