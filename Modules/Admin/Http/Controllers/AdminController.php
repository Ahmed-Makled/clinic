<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // جمع الإحصائيات
        $stats = [
            'doctors_count' => Doctor::count(),
            'patients_count' => User::where('type', 'patient')->count(),
            'specialties_count' => Category::count(),
            'today_appointments' => 0 // سيتم تحديثه لاحقاً عندما نضيف جدول المواعيد
        ];

        // بيانات الرسم البياني - عدد المواعيد في آخر 7 أيام
        $chart = [
            'labels' => [],
            'data' => []
        ];

        // تجهيز بيانات آخر 7 أيام للرسم البياني
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chart['labels'][] = $date->format('Y-m-d');
            $chart['data'][] = 0; // سيتم تحديثه لاحقاً عندما نضيف جدول المواعيد
        }

        // قائمة بآخر المواعيد
        $latestAppointments = []; // سيتم تحديثه لاحقاً

        return view('admin::index', compact('stats', 'chart', 'latestAppointments'));
    }

    public function dashboard()
    {
        $stats = [
            'doctors_count' => Doctor::count(),
            'patients_count' => User::where('type', 'patient')->count(),
            'appointments_count' => 0, // سيتم تحديثه لاحقاً عندما نضيف جدول المواعيد
            'categories_count' => Category::count(),
        ];

        return view('admin::dashboard', [
            'stats' => $stats,
            'title' => 'لوحة التحكم',
            'classes' => 'bg-white'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
