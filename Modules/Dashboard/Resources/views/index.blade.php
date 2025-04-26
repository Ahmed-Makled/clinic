@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('header_icon')
    <i class="bi bi-speedometer2 text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <!-- Doctors Stats -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="card-body">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="stat-value">{{ $stats['doctors'] }}</h3>
                    <p class="stat-label">الأطباء</p>
                    <small class="text-muted">{{ $stats['active_doctors'] }} طبيب نشط</small>
                </div>
            </div>
        </div>

        <!-- Patients Stats -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card success">
                <div class="card-body">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-person"></i>
                    </div>
                    <h3 class="stat-value">{{ $stats['patients'] }}</h3>
                    <p class="stat-label">المرضى</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-gender-male"></i> {{ $stats['male_patients'] }}
                        </small>
                        <small class="text-muted">
                            <i class="bi bi-gender-female"></i> {{ $stats['female_patients'] }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Stats -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card warning">
                <div class="card-body">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h3 class="stat-value">{{ number_format($stats['total_fees']) }} ج.م</h3>
                    <p class="stat-label">إجمالي الرسوم</p>
                    <div class="d-flex justify-content-between">
                        <small class="text-success">
                            <i class="bi bi-check-circle"></i> {{ number_format($stats['paid_fees']) }} ج.م
                        </small>
                        <small class="text-danger">
                            <i class="bi bi-clock"></i> {{ number_format($stats['unpaid_fees']) }} ج.م
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Stats -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card info">
                <div class="card-body">
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h3 class="stat-value">{{ $stats['today_appointments'] }}</h3>
                    <p class="stat-label">مواعيد اليوم</p>
                    <small class="text-muted">{{ $stats['upcoming_appointments'] }} موعد قادم</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Appointments Chart -->
        <div class="col-md-8">
            <div class="chart-container shadow-sm">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="bi bi-graph-up me-2"></i>
                        إحصائيات المواعيد
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary active" data-period="week">أسبوع</button>
                        <button class="btn btn-sm btn-outline-primary" data-period="month">شهر</button>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="appointmentsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Specialties Distribution -->
        <div class="col-md-4">
            <div class="chart-container shadow-sm">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="bi bi-pie-chart-fill me-2"></i>
                        توزيع التخصصات
                    </h5>
                </div>
                <div class="chart-body">
                    <canvas id="specialtiesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity and Performance -->
    <div class="row g-3">
        <!-- Recent Activity -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="bi bi-activity me-2"></i>
                        آخر النشاطات
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush activities-container">
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
        </div>

        <!-- Performance Stats -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge me-2"></i>
                        مؤشرات الأداء
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Completed Appointments Rate -->
                    <div class="quick-stat mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>نسبة المواعيد المكتملة</span>
                            <span class="text-success">{{ $stats['completed_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $stats['completed_rate'] }}%"></div>
                        </div>
                    </div>

                    <!-- Payment Collection Rate -->
                    <div class="quick-stat mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>نسبة تحصيل الرسوم</span>
                            <span class="text-primary">{{ $stats['payment_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $stats['payment_rate'] }}%"></div>
                        </div>
                    </div>

                    <!-- Today's Progress -->
                    <div class="quick-stat">
                        <div class="d-flex justify-content-between mb-1">
                            <span>إنجاز مواعيد اليوم</span>
                            <span class="text-warning">{{ $stats['today_completion_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: {{ $stats['today_completion_rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Stat Cards Enhancement */
            .stat-card {
                background: white;
                border-radius: 15px;
                border: none;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            }

            .stat-card .card-body {
                padding: 1.5rem;
                position: relative;
                z-index: 1;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                font-size: 1.5rem;
                margin-bottom: 1rem;
                position: relative;
            }

            .stat-icon::after {
                content: '';
                position: absolute;
                width: 100%;
                height: 100%;
                background: inherit;
                border-radius: inherit;
                opacity: 0.3;
                transform: scale(1.6);
                z-index: -1;
            }

            .stat-value {
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                color: #2c3e50;
            }

            .stat-label {
                font-size: 1rem;
                font-weight: 600;
                margin-bottom: 0.75rem;
                color: #64748b;
            }

            /* Stat card variants */
            .stat-card.success .stat-icon { background-color: #e6f4ea; color: #34d399; }
            .stat-card.warning .stat-icon { background-color: #fef3c7; color: #f59e0b; }
            .stat-card.info .stat-icon { background-color: #e0f2fe; color: #0ea5e9; }
            
            .stat-card small {
                font-size: 0.875rem;
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
            }

            .stat-card .bi {
                font-size: 1.1rem;
            }

            /* Adding subtle pattern background */
            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.05) 1px, transparent 0);
                background-size: 20px 20px;
                opacity: 0.5;
                z-index: 0;
            }

            /* Progress indicators animation */
            @keyframes countUp {
                from { transform: translateY(10px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }

            .stat-value {
                animation: countUp 0.5s ease-out forwards;
            }

            /* Data Visualization Cards */
            .stat-card {
                position: relative;
                overflow: hidden;
                border: none;
                background: white;
                transition: transform var(--transition-speed) ease;
            }

            .stat-card:hover {
                transform: translateY(-5px);
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: var(--primary-color);
                border-radius: 4px 4px 0 0;
            }

            .stat-card.success::before {
                background: var(--success-color);
            }

            .stat-card.warning::before {
                background: var(--warning-color);
            }

            .stat-card.danger::before {
                background: var(--danger-color);
            }

            .stat-card .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                margin-bottom: 1rem;
                transition: all var(--transition-speed) ease;
            }

            .stat-card:hover .stat-icon {
                transform: scale(1.1);
            }

            .stat-card .stat-value {
                font-size: 1.75rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: var(--primary-color);
            }

            .stat-card .stat-label {
                color: var(--secondary-color);
                font-size: 0.875rem;
                margin-bottom: 0;
            }

            .stat-card .stat-change {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.875rem;
                margin-top: 0.5rem;
            }

            .stat-card .stat-change.positive {
                color: var (--success-color);
            }

            .stat-card .stat-change.negative {
                color: var(--danger-color);
            }

            /* Chart Containers */
            .chart-container {
                position: relative;
                background: white;
                border-radius: 16px;
                padding: 1.5rem;
                height: 100%;
                min-height: 400px;
                display: flex;
                flex-direction: column;
            }

            .chart-container .chart-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .chart-container .chart-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--primary-color);
                margin: 0;
            }

            .chart-container .chart-body {
                flex-grow: 1;
                position: relative;
            }

            /* Activities Container */
            .activities-container {
                max-height: 190px;
                overflow-y: auto;
                scrollbar-width: thin;
            }

            .activities-container::-webkit-scrollbar {
                width: 6px;
            }

            .activities-container::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }

            .activities-container::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 3px;
            }

            .activities-container::-webkit-scrollbar-thumb:hover {
                background: #555;
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
