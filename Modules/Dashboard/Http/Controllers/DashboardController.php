<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $appointmentsStats = $this->getAppointmentsStats();

        $stats = [
            'total_users' => $this->getUsersStats(),
            'doctors' => $this->getDoctorsStats(),
            'patients' => $this->getPatientsStats(),
            'appointments' => $appointmentsStats,
            'financial' => $this->getFinancialStats(),
            'pending_appointments' => Appointment::where('status', 'scheduled')->count(),
            'unpaid_fees' => Appointment::where('is_paid', false)->sum('fees')
        ];

        $stats['completion_rate'] = $appointmentsStats['completion_rate'];

        $chartData = $this->getAppointmentsChartData();
        $specialtyData = $this->getSpecialtiesData();
        $chartData = array_merge($chartData, $specialtyData);

        $activities = $this->getRecentActivities();

        return view('dashboard::index', compact('stats', 'chartData', 'activities'));
    }

    private function getUsersStats()
    {
        return [
            'total' => User::count(),
            'today' => User::whereDate('created_at', Carbon::today())->count(),
            'active' => User::where(function($query) {
                $query->where('last_seen', '>=', Carbon::now()->subMinutes(5))
                      ->orWhere('last_seen', null);
            })->count()
        ];
    }

    private function getDoctorsStats()
    {
        return [
            'total' => Doctor::count(),
            'active' => Doctor::where('status', true)->count()
        ];
    }

    private function getPatientsStats()
    {
        $patientRole = 'Patient';
        return [
            'total' => Patient::count(),
            'new_today' => Patient::whereDate('created_at', Carbon::today())->count(),
            'active' => User::role($patientRole)->where('status', true)->count()
        ];
    }

    private function getAppointmentsStats()
    {
        $completed = Appointment::where('status', 'completed')->count();
        $cancelled = Appointment::where('status', 'cancelled')->count();
        $scheduled = Appointment::where('status', 'scheduled')->count();
        $total = $completed + $cancelled + $scheduled;
        $today = Appointment::whereDate('scheduled_at', Carbon::today())->count();

        return [
            'completed' => $completed,
            'cancelled' => $cancelled,
            'scheduled' => $scheduled,
            'total' => $total,
            'today' => $today,
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100) : 0
        ];
    }

    private function getFinancialStats()
    {
        $collectedAmount = Appointment::where('status', 'completed')
            ->where('is_paid', true)
            ->sum('fees');

        $pendingAmount = Appointment::where('is_paid', false)->sum('fees');

        $totalAmount = $collectedAmount + $pendingAmount;

        return [
            'total_income' => $totalAmount,
            'collected_amount' => $collectedAmount,
            'pending_amount' => $pendingAmount,
            'collection_percentage' => $totalAmount > 0
                ? round(($collectedAmount / $totalAmount) * 100)
                : 100
        ];
    }

    private function getAppointmentsChartData()
    {
        $dates = collect();
        $appointments = collect();
        $period = request('period', 'week');

        switch ($period) {
            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->startOfMonth()->subMonths($i);
                    $dates->push($date->format('Y-m'));
                    $monthCount = Appointment::whereYear('scheduled_at', $date->year)
                        ->whereMonth('scheduled_at', $date->month)
                        ->count();
                    $appointments->push($monthCount);
                }
                break;

            case 'month':
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $dates->push($date->format('Y-m-d'));
                    $dayCount = Appointment::whereDate('scheduled_at', $date)->count();
                    $appointments->push($dayCount);
                }
                break;

            default: // week
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $dates->push($date->format('Y-m-d'));
                    $dayCount = Appointment::whereDate('scheduled_at', $date)->count();
                    $appointments->push($dayCount);
                }
                break;
        }

        return [
            'labels' => $dates->toArray(),
            'appointments' => $appointments->toArray()
        ];
    }

    private function getSpecialtiesData()
    {
        $specialties = Category::select('categories.*')
            ->withCount(['doctors'])
            ->withCount(['doctors as active_doctors_count' => function($query) {
                $query->where('doctors.status', true);
            }])
            ->leftJoin('doctor_category', 'categories.id', '=', 'doctor_category.category_id')
            ->leftJoin('doctors', 'doctor_category.doctor_id', '=', 'doctors.id')
            ->leftJoin('appointments', function($join) {
                $join->on('doctors.id', '=', 'appointments.doctor_id')
                     ->where('appointments.status', '=', 'completed');
            })
            ->groupBy('categories.id')
            ->selectRaw('COALESCE(SUM(appointments.fees), 0) as appointments_sum_fees')
            ->orderByDesc('doctors_count')
            ->take(5)
            ->get();

        return [
            'specialtyLabels' => $specialties->pluck('name')->toArray(),
            'specialtyCounts' => $specialties->pluck('doctors_count')->toArray(),
            'activeSpecialtyCounts' => $specialties->pluck('active_doctors_count')->toArray(),
            'specialtyIncome' => $specialties->pluck('appointments_sum_fees')->toArray()
        ];
    }

    private function getRecentActivities()
    {
        return Appointment::with(['doctor', 'patient'])
            ->latest('scheduled_at')
            ->take(10)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => sprintf('موعد مع د. %s', $appointment->doctor->name),
                    'description' => sprintf('موعد للمريض %s', $appointment->patient->name),
                    'doctor_name' => $appointment->doctor->name,
                    'patient_name' => $appointment->patient->name,
                    'status' => $appointment->status,
                    'status_color' => $appointment->status_color,
                    'scheduled_at' => $appointment->scheduled_at,
                    'fees' => $appointment->fees,
                    'is_paid' => $appointment->is_paid
                ];
            });
    }

    public function getChartData()
    {
        $chartData = $this->getAppointmentsChartData();
        return response()->json($chartData);
    }
}

