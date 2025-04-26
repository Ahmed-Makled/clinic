@extends('layouts.admin')

@section('title', 'لوحة التحكم')

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
                <div class="list-group list-group-flush">
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
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['completed_rate'] }}%"></div>
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
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['today_completion_rate'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Appointments Chart
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appointmentsCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'المواعيد',
                data: @json($chartData['appointments']),
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-color'),
                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-bg-subtle'),
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

    // Specialties Chart
    const specialtiesCtx = document.getElementById('specialtiesChart').getContext('2d');
    new Chart(specialtiesCtx, {
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
                ]
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
            }
        }
    });
});
</script>

<style>
.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.activity-icon.bg-success {
    background: var(--success-bg-subtle);
    color: var(--success-color);
}

.activity-icon.bg-warning {
    background: var(--warning-bg-subtle);
    color: var(--warning-color);
}

.activity-icon.bg-danger {
    background: var(--danger-bg-subtle);
    color: var(--danger-color);
}

.quick-stat {
    position: relative;
}

.progress {
    background-color: var(--border-color);
    overflow: hidden;
    border-radius: 12px;
}

.progress-bar {
    background-color: var(--primary-color);
    transition: width 0.6s ease;
}
</style>
@endpush

@endsection
