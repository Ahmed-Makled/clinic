@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('header_icon')
    <i class="bi bi-speedometer2 text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection

@section('content')
    <!-- Main Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card appointments">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">الحجوزات</h3>
                        <div class="stat-total">
                            {{ $stats['appointments']['total'] }}
                            <small class="text-muted ms-1">إجمالي</small>
                        </div>
                        <div class="stat-breakdown mt-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ $stats['appointments']['completed'] }} مكتمل
                                </span>
                                <span style="color: #9333ea">
                                    <i class="bi bi-clock-fill"></i>
                                    {{ $stats['appointments']['scheduled'] }} منتظر
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card patients">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">المرضى</h3>
                        <div class="stat-total">
                            {{ $stats['patients']['total'] }}
                            <small class="text-muted ms-1">مريض</small>
                        </div>
                        <div class="stat-breakdown mt-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-success">
                                    <i class="bi bi-check-circle"></i>
                                    {{ $stats['patients']['active'] }} نشط
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card doctors">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">الأطباء</h3>
                        <div class="stat-total">
                            {{ $stats['doctors']['total'] }}
                            <small class="text-muted ms-1">طبيب</small>
                        </div>
                        <div class="stat-breakdown mt-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-success">
                                    <i class="bi bi-check-circle"></i>
                                    {{ $stats['doctors']['active'] }} نشط
                                </span>
                                @if(isset($stats['doctors']['incomplete']) && $stats['doctors']['incomplete'] > 0)
                                <span class="text-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <a href="{{ route('doctors.incomplete_profiles') }}" class="text-warning text-decoration-none">
                                        {{ $stats['doctors']['incomplete'] }} غير مكتمل
                                    </a>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card finance">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">المالية</h3>
                        <div class="stat-total">
                            {{ number_format($stats['financial']['total_income']) }}
                            <small class="text-muted ms-1">ج.م</small>
                        </div>
                        <div class="stat-breakdown mt-2">
                            <div class="d-flex aling-items-center justify-content-between ">
                                <span class="text-success">
                                    <i class="bi bi-check-circle"></i>
                                    {{ number_format($stats['financial']['collected_amount']) }} ج.م
                                    <P>تم تحصيله</P>
                                </span>
                                <span class="text-danger">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ number_format($stats['financial']['pending_amount']) }} ج.م
                                    <P>مستحقات</P>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="row g-4 mb-4">
        <!-- Performance Chart -->
        <div class="col-md-8">
            <div class="card chart-card">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            تحليل الحجوزات
                        </h5>
                        <div class="chart-actions">
                            <select class="form-select form-select-sm bg-light border-0 rounded-pill px-3" style="min-width: 120px;" id="chartPeriod">
                                <option value="week" selected>آخر أسبوع</option>
                                <option value="month">آخر شهر</option>
                                <option value="year">آخر سنة</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="appointmentsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribution Chart -->
        <div class="col-md-4">
            <div class="card chart-card">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart me-2"></i>
                        توزيع التخصصات
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="specialtiesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity & Notifications -->
    <div class="row g-4">
        <!-- Recent Activity -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-activity me-2"></i>
                            آخر النشاطات
                        </h5>
                        <span class="badge bg-primary-subtle text-primary">اليوم</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="activity-list">
                        @forelse($activities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon {{ $activity['status_color'] }}">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                        <small class="text-muted">
                                            {{ Carbon\Carbon::parse($activity['scheduled_at'])->format('h:i A') }}
                                        </small>
                                    </div>
                                    <p class="mb-0">{{ $activity['description'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x display-4 text-muted"></i>
                                    <p class="mt-2">لا توجد نشاطات حديثة</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <style>
                .activity-list {
                    max-height: 500px;
                    overflow-y: auto;
                }

                .activity-item {
                    display: flex;
                    align-items: flex-start;
                    gap: 1rem;
                    padding: 1rem;
                    border-bottom: 1px solid #f1f5f9;
                    transition: all 0.3s ease;
                }

                .activity-item:hover {
                    background-color: #f8fafc;
                }

                .activity-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.25rem;
                    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%);
                    color: #9333ea;
                }

                .activity-content {
                    flex: 1;
                }

                .activity-content h6 {
                    color: #1e293b;
                    font-weight: 500;
                    font-size: 1rem;
                }

                .activity-content p {
                    color: #64748b;
                    font-size: 0.875rem;
                    margin: 0;
                }

                .empty-state {
                    color: #94a3b8;
                    text-align: center;
                    padding: 2rem;
                }
            </style>
        </div>

        <!-- Notifications -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">
                        <i class="bi bi-bell me-2"></i>
                        التنبيهات
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="notification-list">
                        @if($stats['pending_appointments'] > 0)
                            <div class="notification-item warning">
                                <i class="bi bi-calendar2-week"></i>
                                <div class="notification-content">
                                    <h6 class="mb-1">مواعيد في الانتظار</h6>
                                    <p class="mb-2">{{ $stats['pending_appointments'] }} حجز في قائمة الانتظار</p>
                                    <a href="{{ route('appointments.index', ['status_filter' => 'pending']) }}"
                                       class="btn btn-sm btn-warning">عرض الحجوزات</a>
                                </div>
                            </div>
                        @endif

                        @if($stats['unpaid_fees'] > 0)
                            <div class="notification-item danger">
                                <i class="bi bi-cash-stack"></i>
                                <div class="notification-content">
                                    <h6 class="mb-1">مدفوعات معلقة</h6>
                                    <p class="mb-2">{{ number_format($stats['unpaid_fees']) }} ج.م في انتظار التحصيل</p>
                                    <a href="{{ route('appointments.index', ['payment_status' => 'unpaid']) }}"
                                       class="btn btn-sm btn-danger">إدارة المدفوعات</a>
                                </div>
                            </div>
                        @endif

                        @if(isset($stats['doctors']['incomplete']) && $stats['doctors']['incomplete'] > 0)
                            <div class="notification-item warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <div class="notification-content">
                                    <h6 class="mb-1">بيانات أطباء غير مكتملة</h6>
                                    <p class="mb-2">{{ $stats['doctors']['incomplete'] }} طبيب بحاجة لاستكمال البيانات</p>
                                    <a href="{{ route('doctors.incomplete_profiles') }}"
                                       class="btn btn-sm btn-warning">عرض الملفات</a>
                                </div>
                            </div>
                        @endif

                        @if(!$stats['pending_appointments'] && !$stats['unpaid_fees'])
                            <div class="text-center py-4">
                                <div class="empty-state success">
                                    <i class="bi bi-check-circle display-4"></i>
                                    <p class="mt-2">لا توجد تنبيهات حالياً</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            padding-bottom: 8px;
            height: 100%;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease;
        }

        .stat-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-icon i::before{
            line-height: 2;
        }

        .appointments .stat-icon {
            background-color: var(--primary-bg-subtle);
            color: var(--primary-color);
        }

        .patients .stat-icon {
            background-color: var(--success-bg-subtle);
            color: var(--success-color);
        }

        .doctors .stat-icon {
            background-color: var(--info-bg-subtle);
            color: var(--info-color);
        }

        .finance .stat-icon {
            background-color: var(--warning-bg-subtle);
            color: var(--warning-color);
        }

        .stat-info {
            flex: 1;
        }

        .stat-title {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .stat-total {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
        }

        .stat-breakdown {
            font-size: 0.875rem;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            height: 100%;
        }

        .activity-list, .notification-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%);
            color: #9333ea;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f8fafc;
        }

        .notification-item i {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .notification-item.warning i {
            background-color: var(--warning-bg-subtle);
            color: var(--warning-color);
        }

        .notification-item.danger i {
            background-color: var(--danger-bg-subtle);
            color: var(--danger-color);
        }

        .empty-state {
            color: #94a3b8;
        }

        .empty-state.success {
            color: var(--success-color);
        }

        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let appointmentsChart = null;

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize charts
                initCharts(@json($chartData));
                // Handle period change
                document.getElementById('chartPeriod').addEventListener('change', function() {
                    updateChart(this.value);
                });
            });

            function updateChart(period) {
                fetch(`/dashboard/chart-data?period=${period}`)
                    .then(response => response.json())
                    .then(data => {
                        if (appointmentsChart) {
                            appointmentsChart.destroy();
                        }
                        createAppointmentsChart(data);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function initCharts(data) {
                createAppointmentsChart(data);
                createSpecialtiesChart(data);
            }

            function createAppointmentsChart(data) {
                const appointmentsCtx = document.getElementById('appointmentsChart');
                if (appointmentsCtx) {
                    appointmentsChart = new Chart(appointmentsCtx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'الحجوزات',
                                data: data.appointments,
                                borderColor: getComputedStyle(document.documentElement)
                                    .getPropertyValue('--primary-color'),
                                backgroundColor: getComputedStyle(document.documentElement)
                                    .getPropertyValue('--primary-bg-subtle'),
                                fill: true,
                                tension: 0.4
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
            }

            function createSpecialtiesChart(data) {
                const specialtiesCtx = document.getElementById('specialtiesChart');
                if (specialtiesCtx) {
                    new Chart(specialtiesCtx, {
                        type: 'doughnut',
                        data: {
                            labels: data.specialtyLabels,
                            datasets: [{
                                data: data.specialtyCounts,
                                backgroundColor: [
                                    '#3b82f6',
                                    '#10b981',
                                    '#f97316',
                                    '#6366f1',
                                    '#ec4899'
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
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            },
                            cutout: '60%'
                        }
                    });
                }
            }
        </script>
    @endpush
@endsection
