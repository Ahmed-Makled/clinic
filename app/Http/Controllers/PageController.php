<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

class PageController extends Controller
{
    public function home()
    {
        return view('home', [
            'title' => 'Clinic Master',
            'classes' => 'bg-white',
            'categories' => [],
            'governorates' => Config::get('governorates', []),
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
}
