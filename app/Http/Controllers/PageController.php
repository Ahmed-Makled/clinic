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
            'categories' => Category::where('status', 'active')->get(),
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

}
