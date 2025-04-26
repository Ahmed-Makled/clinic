@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('header_icon')
    <i class="bi bi-speedometer2 text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection

@section('content')
    <!-- Main Stats Overview -->
    <div class="row g-4 mb-4">
        <!-- Today's Overview Card -->
        <div class="col-12">
            <div class="card overview-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="overview-title mb-3">نظرة عامة اليوم</h4>
                            <div class="today-stats">
                                <div class="stat-item">
                                    <i class="bi bi-calendar-check-fill text-primary"></i>
                                    <span class="stat-value">{{ $stats['today_appointments'] }}</span>
                                    <span class="stat-label">مواعيد اليوم</span>
                                </div>
                                <div class="stat-item">
                                    <i class="bi bi-clock-fill text-warning"></i>
                                    <span class="stat-value">{{ $stats['upcoming_appointments'] }}</span>
                                    <span class="stat-label">المواعيد القادمة</span>
                                </div>
                                <div class="stat-item">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    <span class="stat-value">{{ $stats['completed_rate'] }}%</span>
                                    <span class="stat-label">نسبة الإنجاز</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics Grid -->
    <div class="row g-4 mb-4">
        <!-- Users Statistics -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card users">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">الأطباء والمرضى</h3>
                        <div class="stat-total">
                            <span class="total-number">{{ $stats['doctors'] + $stats['patients'] }}</span>
                            <span class="total-label">إجمالي المستخدمين</span>
                        </div>
                    </div>
                </div>
                <div class="stat-details">
                    <div class="detail-item">
                        <i class="bi bi-heart-pulse"></i>
                        <span class="detail-value">{{ $stats['doctors'] }}</span>
                        <span class="detail-label">طبيب</span>
                        <small class="detail-sub">({{ $stats['active_doctors'] }} نشط)</small>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-people"></i>
                        <span class="detail-value">{{ $stats['patients'] }}</span>
                        <span class="detail-label">مريض</span>
                        <div class="gender-stats">
                            <small class="male"><i class="bi bi-gender-male"></i> {{ $stats['male_patients'] }}</small>
                            <small class="female"><i class="bi bi-gender-female"></i> {{ $stats['female_patients'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Statistics -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card finance">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">الإحصائيات المالية</h3>
                        <div class="stat-total">
                            <span class="total-number">{{ number_format($stats['total_fees']) }}</span>
                            <span class="total-label">ج.م إجمالي</span>
                        </div>
                    </div>
                </div>
                <div class="stat-details">
                    <div class="progress-container">
                        <div class="progress finance-progress">
                            <div class="progress-bar bg-success" style="width: {{ ($stats['paid_fees'] / $stats['total_fees']) * 100 }}%"></div>
                        </div>
                        <div class="progress-stats">
                            <div class="collected">
                                <i class="bi bi-check-circle text-success"></i>
                                <span>{{ number_format($stats['paid_fees']) }} ج.م</span>
                                <small>محصل</small>
                            </div>
                            <div class="pending">
                                <i class="bi bi-clock text-warning"></i>
                                <span>{{ number_format($stats['unpaid_fees']) }} ج.م</span>
                                <small>معلق</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Statistics -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card appointments">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">إحصائيات المواعيد</h3>
                        <div class="stat-total">
                            <span class="total-number">{{ $stats['today_appointments'] }}</span>
                            <span class="total-label">موعد اليوم</span>
                        </div>
                    </div>
                </div>
                <div class="stat-details appointments-progress">
                    <div class="status-item">
                        <div class="status-info">
                            <span class="status-label">مكتمل</span>
                            <span class="status-value">{{ $stats['completed_rate'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $stats['completed_rate'] }}%"></div>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-info">
                            <span class="status-label">قيد الانتظار</span>
                            <span class="status-value">{{ 100 - $stats['completed_rate'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: {{ 100 - $stats['completed_rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card performance">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">مؤشرات الأداء</h3>
                    </div>
                </div>
                <div class="stat-details">
                    <div class="performance-metric">
                        <div class="metric-info">
                            <span class="metric-label">رضا المرضى</span>
                            <span class="metric-value">{{ $stats['satisfaction_rate'] ?? '95' }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: {{ $stats['satisfaction_rate'] ?? '95' }}%"></div>
                        </div>
                    </div>
                    <div class="performance-metric">
                        <div class="metric-info">
                            <span class="metric-label">معدل الحضور</span>
                            <span class="metric-value">{{ $stats['attendance_rate'] ?? '88' }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: {{ $stats['attendance_rate'] ?? '88' }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Appointments Trend -->
        <div class="col-md-8">
            <div class="card chart-card">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            اتجاه المواعيد
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="appointmentsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Specialties Distribution -->
        <div class="col-md-4">
            <div class="card chart-card">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart-fill me-2"></i>
                        توزيع التخصصات
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="specialtiesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card activities-card">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="bi bi-activity me-2"></i>
                        آخر النشاطات
                    </h5>
                </div>
                <div class="activities-container">
                    @forelse($activities as $appointment)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon {{ $appointment->status_color }}">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-1">
                                        موعد جديد للمريض
                                        <strong>{{ $appointment->patient->name }}</strong>
                                        مع الدكتور
                                        <strong>{{ $appointment->doctor->name }}</strong>
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $appointment->scheduled_at->format('Y-m-d h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-calendar-x display-6 d-block mb-3"></i>
                                <p class="h5">لا توجد نشاطات حديثة</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions & Reminders -->
        <div class="col-md-4">
            <div class="card reminders-card">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="bi bi-bell me-2"></i>
                        التذكيرات
                    </h5>
                </div>
                <div class="card-body">
                    <div class="reminder-list">
                        @if($stats['pending_appointments'] > 0)
                            <div class="reminder-item warning">
                                <i class="bi bi-exclamation-circle"></i>
                                <span>{{ $stats['pending_appointments'] }} مواعيد في قائمة الانتظار</span>
                            </div>
                        @endif
                        @if($stats['unpaid_fees'] > 0)
                            <div class="reminder-item danger">
                                <i class="bi bi-cash"></i>
                                <span>{{ number_format($stats['unpaid_fees']) }} ج.م رسوم غير محصلة</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Main Layout Styles */
            .card {
                border: none;
                box-shadow: 0 0 20px rgba(0,0,0,0.05);
                border-radius: 15px;
                overflow: hidden;
            }

            /* Overview Card */
            .overview-card {
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                margin-bottom: 2rem;
            }

            .overview-title {
                color: #2c3e50;
                font-weight: 600;
            }

            .today-stats {
                display: flex;
                gap: 4rem;
                margin-top: 1rem;
            }

            .stat-item {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .stat-item i {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .stat-value {
                font-size: 1.8rem;
                font-weight: 700;
                line-height: 1;
                color: #2c3e50;
            }

            .stat-label {
                color: #64748b;
                font-size: 0.9rem;
            }

            /* Quick Actions */
            .quick-actions {
                background: #f8f9fa;
                padding: 1.5rem;
                border-radius: 12px;
            }

            .action-buttons {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            /* Stat Cards */
            .stat-card {
                background: white;
                padding: 1.5rem;
                border-radius: 15px;
                height: 100%;
            }

            .stat-header {
                display: flex;
                align-items: flex-start;
                margin-bottom: 1.5rem;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                margin-left: 1rem;
            }

            .users .stat-icon { background: #e8f5e9; color: #2e7d32; }
            .finance .stat-icon { background: #fff8e1; color: #f57c00; }
            .appointments .stat-icon { background: #e3f2fd; color: #1976d2; }
            .performance .stat-icon { background: #f3e5f5; color: #7b1fa2; }

            .stat-info {
                flex: 1;
            }

            .stat-title {
                font-size: 1rem;
                font-weight: 600;
                color: #64748b;
                margin-bottom: 0.5rem;
            }

            .stat-details {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid #e2e8f0;
            }

            /* Progress Bars */
            .progress {
                height: 8px;
                margin-bottom: 0.5rem;
                background-color: #f1f1f1;
            }

            .progress-bar {
                border-radius: 4px;
            }

            /* Chart Cards */
            .chart-card {
                height: 100%;
                min-height: 400px;
            }

            .chart-period .btn {
                padding: 0.25rem 0.75rem;
                font-size: 0.875rem;
            }

            /* Activities Card */
            .activities-container {
                max-height: 400px;
                overflow-y: auto;
            }

            .activity-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
            }

            /* Performance Metrics */
            .performance-metric {
                margin-bottom: 1rem;
            }

            .metric-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
            }

            /* Reminder Card */
            .reminder-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .reminder-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem;
                border-radius: 8px;
                background: #f8f9fa;
            }

            .reminder-item.warning { background: #fff8e1; color: #f57c00; }
            .reminder-item.danger { background: #ffebee; color: #d32f2f; }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .today-stats {
                    flex-direction: column;
                    gap: 1rem;
                }

                .quick-actions {
                    margin-top: 1rem;
                }

                .action-buttons {
                    flex-direction: column;
                }

                .action-buttons .btn {
                    width: 100%;
                }
            }


        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let appointmentsChart;

                // Function to initialize or update appointments chart
                function initAppointmentsChart(data) {
                    const appointmentsCtx = document.getElementById('appointmentsChart');
                    if (!appointmentsCtx) return;

                    if (appointmentsChart) {
                        appointmentsChart.destroy();
                    }

                    appointmentsChart = new Chart(appointmentsCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'المواعيد',
                                data: data.appointments,
                                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-color'),
                                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-bg-subtle'),
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }

                // Initialize charts with initial data
                initAppointmentsChart(@json($chartData));

                // Initialize specialties chart
                const specialtiesCtx = document.getElementById('specialtiesChart');
                if (specialtiesCtx) {
                    new Chart(specialtiesCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($chartData['specialtyLabels']),
                            datasets: [{
                                data: @json($chartData['specialtyCounts']),
                                backgroundColor: [
                                    getComputedStyle(document.documentElement).getPropertyValue('--primary-color'),
                                    getComputedStyle(document.documentElement).getPropertyValue('--success-color'),
                                    getComputedStyle(document.documentElement).getPropertyValue('--warning-color'),
                                    getComputedStyle(document.documentElement).getPropertyValue('--info-color'),
                                    getComputedStyle(document.documentElement).getPropertyValue('--danger-color')
                                ],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20
                                    }
                                }
                            },
                            cutout: '70%'
                        }
                    });
                }

                // Handle period switching
                const periodButtons = document.querySelectorAll('[data-period]');
                periodButtons.forEach(button => {
                    button.addEventListener('click', async function () {
                        // Update active state
                        periodButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');

                        // Fetch new data
                        const period = this.dataset.period;
                        try {
                            const response = await fetch(`/dashboard/chart-data?period=${period}`);
                            if (!response.ok) throw new Error('Failed to fetch data');
                            const data = await response.json();
                            initAppointmentsChart(data);
                        } catch (error) {
                            console.error('Error updating chart:', error);
                        }
                    });
                });
            });</script>
    @endpush


@endsection
