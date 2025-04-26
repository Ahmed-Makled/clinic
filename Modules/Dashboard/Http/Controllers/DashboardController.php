<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // General Statistics with caching
        $stats = [
            'doctors' => Cache::remember('doctors_count', 3600, function () {
                return Doctor::count();
            }),
            'active_doctors' => Cache::remember('active_doctors_count', 3600, function () {
                return Doctor::where('status', true)->count();
            }),
            'patients' => Cache::remember('patients_count', 3600, function () {
                return Patient::count();
            }),
            'male_patients' => Cache::remember('male_patients_count', 3600, function () {
                return Patient::where('gender', 'male')->count();
            }),
            'female_patients' => Cache::remember('female_patients_count', 3600, function () {
                return Patient::where('gender', 'female')->count();
            }),
            'appointments' => Cache::remember('appointments_count', 3600, function () {
                return Appointment::count();
            }),
            'today_appointments' => Cache::remember('today_appointments', 300, function () {
                return Appointment::whereDate('scheduled_at', Carbon::today())->count();
            }),
            'upcoming_appointments' => Cache::remember('upcoming_appointments', 300, function () {
                return Appointment::where('scheduled_at', '>', Carbon::now())->count();
            }),
            'total_fees' => Cache::remember('total_fees', 3600, function () {
                return Appointment::sum('fees');
            }),
            'paid_fees' => Cache::remember('paid_fees', 3600, function () {
                return Appointment::where('is_paid', true)->sum('fees');
            }),
            'unpaid_fees' => Cache::remember('unpaid_fees', 3600, function () {
                return Appointment::where('is_paid', false)->sum('fees');
            }),
            'completed_rate' => Cache::remember('completed_rate', 3600, function () {
                $total = Appointment::count();
                if ($total === 0) return 0;
                $completed = Appointment::where('status', 'completed')->count();
                return round(($completed / $total) * 100);
            }),
            'payment_rate' => Cache::remember('payment_rate', 3600, function () {
                $total = Appointment::sum('fees');
                if ($total === 0) return 0;
                $paid = Appointment::where('is_paid', true)->sum('fees');
                return round(($paid / $total) * 100);
            }),
            'today_completion_rate' => Cache::remember('today_completion_rate', 300, function () {
                $total = Appointment::whereDate('scheduled_at', Carbon::today())->count();
                if ($total === 0) return 0;
                $completed = Appointment::whereDate('scheduled_at', Carbon::today())
                    ->where('status', 'completed')
                    ->count();
                return round(($completed / $total) * 100);
            }),
            'pending_appointments' => Cache::remember('pending_appointments', 300, function () {
                return Appointment::where('status', 'pending')
                    ->where('scheduled_at', '>=', Carbon::now())
                    ->count();
            })
        ];

        // Appointment chart data
        $chartData = $this->getAppointmentsChartData();

        // Specialties distribution data
        $specialtyData = $this->getSpecialtiesData();
        $chartData = array_merge($chartData, $specialtyData);

        // Recent activities
        $activities = Appointment::with(['doctor', 'patient'])
            ->latest('scheduled_at')
            ->take(5)
            ->get();

        return view('dashboard::index', compact('stats', 'chartData', 'activities'));
    }

    private function getAppointmentsChartData()
    {
        $dates = collect();
        $appointmentCounts = collect();

        // Collect last 7 days data
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates->push($date->format('Y-m-d'));

            $count = Cache::remember('appointments_count_' . $date->format('Y-m-d'), 3600, function () use ($date) {
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
        $specialties = Cache::remember('specialties_distribution', 3600, function () {
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

