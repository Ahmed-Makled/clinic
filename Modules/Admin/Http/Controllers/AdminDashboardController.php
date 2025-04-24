<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'doctors' => Doctor::count(),
            'patients' => Patient::count(),
            'appointments' => Appointment::count(),
        ];

        return view('admin::dashboard', [
            'stats' => $stats,
            'title' => 'لوحة التحكم',
            'classes' => 'bg-white'
        ]);
    }
}
