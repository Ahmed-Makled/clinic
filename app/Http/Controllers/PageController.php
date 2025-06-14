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
            'title' => 'الرئيسية - Clinic Master',
            'classes' => 'bg-white',
            'categories' => Category::where('status', 1 )->get(),
            'governorates' => Governorate::with('cities')->get(),
            'cities' => City::all(),
            'doctors' => Doctor::with(['governorate', 'city', 'category'])
                         ->where('status', true)
                         ->where('is_profile_completed', true)
                         ->select('id', 'name', 'governorate_id', 'city_id', 'category_id')
                         ->get(),
        ]);
    }

    public function about()
    {
        return view('about', [
            'title' => 'عن العيادة - Clinic Master',
            'classes' => 'bg-white'
        ]);
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

    public function getAllCities()
    {
        $cities = City::orderBy('name')->get()->map(function($city) {
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
            ->where('is_profile_completed', true)
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
            'title' => 'نتائج البحث - Clinic Master',
            'classes' => 'bg-white',
            'doctors' => $doctors,
            'categories' => Category::where('status', 1)->get(),
            'governorates' => Governorate::with('cities')->get()
        ]);
    }
}
