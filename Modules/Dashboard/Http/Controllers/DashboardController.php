<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Doctors\Entities\Doctor;
use Modules\Patients\Entities\Patient;
use Modules\Appointments\Entities\Appointment;
use Modules\Payments\Entities\Payment;
use Modules\Specialties\Entities\Category;
use Modules\Users\Entities\User;
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
            'unpaid_fees' => Appointment::whereDoesntHave('payment', function($query) {
                $query->where('status', 'completed');
            })->sum('fees')
        ];

        $stats['completion_rate'] = $appointmentsStats['completion_rate'];

        $chartData = $this->getAppointmentsChartData();
        $specialtyData = $this->getSpecialtiesData();
        $chartData = array_merge($chartData, $specialtyData);

        $activities = $this->getRecentActivities();

        return view('dashboard::admin.index', [
            'title' => 'لوحة التحكم - Clinic Master',
            'stats' => $stats,
            'chartData' => $chartData,
            'activities' => $activities
        ]);
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
            'active' => Doctor::where('status', true)->count(),
            'incomplete' => Doctor::where(function($query) {
                            $query->where('is_profile_completed', false)
                                 ->orWhereNull('is_profile_completed');
                        })->count()
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
        $collectedAmount = Appointment::whereHas('payment', function($query) {
            $query->where('status', 'completed');
        })->sum('fees');

        $pendingAmount = Appointment::whereDoesntHave('payment', function($query) {
            $query->where('status', 'completed');
        })->sum('fees');

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
                // Get data for past 12 months + upcoming appointments for next 12 months
                $startDate = Carbon::now()->startOfMonth()->subMonths(11);
                $endDate = Carbon::now()->addMonths(12);

                // First add the past 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->startOfMonth()->subMonths($i);
                    $dates->push($date->format('Y-m'));
                    $monthCount = Appointment::whereYear('scheduled_at', $date->year)
                        ->whereMonth('scheduled_at', $date->month)
                        ->count();
                    $appointments->push($monthCount);
                }

                // Then get upcoming appointments grouped by month and add them to chart
                $upcomingAppointments = Appointment::select(
                    DB::raw('DATE_FORMAT(scheduled_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('scheduled_at', '>', Carbon::now()->endOfMonth())
                ->where('scheduled_at', '<=', $endDate)
                ->groupBy(DB::raw('DATE_FORMAT(scheduled_at, "%Y-%m")'))
                ->orderBy('month')
                ->get();

                foreach ($upcomingAppointments as $appointment) {
                    $dates->push($appointment->month);
                    $appointments->push($appointment->count);
                }
                break;

            case 'month':
                // Get data for past 30 days + upcoming appointments for next 60 days
                $startDate = Carbon::now()->subDays(29); // Last 30 days including today
                $endDate = Carbon::now()->addDays(60);

                // First add the past 30 days
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $dates->push($date->format('Y-m-d'));
                    $dayCount = Appointment::whereDate('scheduled_at', $date)->count();
                    $appointments->push($dayCount);
                }

                // Then get upcoming appointments and add them to chart
                $upcomingAppointments = Appointment::select(
                    DB::raw('DATE(scheduled_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('scheduled_at', '>', Carbon::now())
                ->where('scheduled_at', '<=', $endDate)
                ->groupBy(DB::raw('DATE(scheduled_at)'))
                ->orderBy('date')
                ->get();

                foreach ($upcomingAppointments as $appointment) {
                    $dates->push(Carbon::parse($appointment->date)->format('Y-m-d'));
                    $appointments->push($appointment->count);
                }
                break;

            default: // week
                // Get data for past week + upcoming appointments for next 3 weeks
                $startDate = Carbon::now()->subDays(6); // Last 7 days including today
                $endDate = Carbon::now()->addDays(21); // Next 3 weeks

                // First add the past 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $dates->push($date->format('Y-m-d'));
                    $dayCount = Appointment::whereDate('scheduled_at', $date)->count();
                    $appointments->push($dayCount);
                }

                // Then get upcoming appointments and add them to chart
                $upcomingAppointments = Appointment::select(
                    DB::raw('DATE(scheduled_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('scheduled_at', '>', Carbon::now())
                ->where('scheduled_at', '<=', $endDate)
                ->groupBy(DB::raw('DATE(scheduled_at)'))
                ->orderBy('date')
                ->get();

                foreach ($upcomingAppointments as $appointment) {
                    $dates->push(Carbon::parse($appointment->date)->format('Y-m-d'));
                    $appointments->push($appointment->count);
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
            ->leftJoin('doctors', 'categories.id', '=', 'doctors.category_id')
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
        return Appointment::with(['doctor', 'patient', 'payment'])
            ->latest('scheduled_at')
            ->take(10)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => sprintf('حجز مع د. %s', $appointment->doctor->name),
                    'description' => sprintf('حجز للمريض %s', $appointment->patient->name),
                    'doctor_name' => $appointment->doctor->name,
                    'patient_name' => $appointment->patient->name,
                    'status' => $appointment->status,
                    'status_color' => $appointment->status_color,
                    'scheduled_at' => $appointment->scheduled_at,
                    'fees' => $appointment->fees,
                    'is_paid' => $appointment->payment && $appointment->payment->status === 'completed'
                ];
            });
    }

    public function getChartData()
    {
        $chartData = $this->getAppointmentsChartData();
        return response()->json($chartData);
    }
}

