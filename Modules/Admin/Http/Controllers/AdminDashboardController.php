<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // إحصائيات عامة
        $stats = [
            'doctors' => Cache::remember('doctors_count', 3600, function() {
                return Doctor::count();
            }),
            'patients' => Cache::remember('patients_count', 3600, function() {
                return Patient::count();
            }),
            'appointments' => Cache::remember('appointments_count', 3600, function() {
                return Appointment::count();
            }),
            'today_appointments' => Cache::remember('today_appointments', 300, function() {
                return Appointment::whereDate('scheduled_at', Carbon::today())->count();
            }),
            'total_fees' => Cache::remember('total_fees', 3600, function() {
                return Appointment::sum('fees');
            }),
            'paid_fees' => Cache::remember('paid_fees', 3600, function() {
                return Appointment::where('is_paid', true)->sum('fees');
            }),
            'unpaid_fees' => Cache::remember('unpaid_fees', 3600, function() {
                return Appointment::where('is_paid', false)->sum('fees');
            })
        ];

        // بيانات الرسم البياني للمواعيد
        $chartData = $this->getAppointmentsChartData();

        // بيانات توزيع التخصصات
        $specialtyData = $this->getSpecialtiesData();
        $chartData = array_merge($chartData, $specialtyData);

        // آخر النشاطات
        $activities = Appointment::with(['doctor', 'patient'])
            ->latest('scheduled_at')
            ->take(5)
            ->get();

        return view('admin::dashboard', compact('stats', 'chartData', 'activities'));
    }

    private function getAppointmentsChartData()
    {
        $dates = collect();
        $appointmentCounts = collect();

        // جمع بيانات آخر 7 أيام
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates->push($date->format('Y-m-d'));

            $count = Cache::remember('appointments_count_'.$date->format('Y-m-d'), 3600, function() use ($date) {
                return Appointment::whereDate('scheduled_at', $date)->count();
            });

            $appointmentCounts->push($count);
        }

        return [
            'labels' => $dates->toArray(),
            'appointments' => $appointmentCounts->toArray(),
        ];
    }

    private function getSpecialtiesData()
    {
        $specialties = Cache::remember('specialties_distribution', 3600, function() {
            return Category::withCount('doctors')
                ->orderByDesc('doctors_count')
                ->take(5)
                ->get();
        });

        return [
            'specialtyLabels' => $specialties->pluck('name')->toArray(),
            'specialtyCounts' => $specialties->pluck('doctors_count')->toArray(),
        ];
    }
}
