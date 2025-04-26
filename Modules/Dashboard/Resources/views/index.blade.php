@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('content')
<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="card-body">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="stat-value">{{ $stats['doctors'] }}</h3>
                <p class="stat-label">الأطباء</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card success">
            <div class="card-body">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-person"></i>
                </div>
                <h3 class="stat-value">{{ $stats['patients'] }}</h3>
                <p class="stat-label">المرضى</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card warning">
            <div class="card-body">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3 class="stat-value">{{ number_format($stats['total_fees']) }} ج.م</h3>
                <p class="stat-label">إجمالي الرسوم</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card info">
            <div class="card-body">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3 class="stat-value">{{ $stats['today_appointments'] }}</h3>
                <p class="stat-label">مواعيد اليوم</p>
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

<!-- Recent Activity and Quick Stats -->
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

    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-charge me-2"></i>
                    إحصائيات سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="quick-stat mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span>نسبة المواعيد المكتملة</span>
                        <span class="text-success">75%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                    </div>
                </div>

                <div class="quick-stat mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span>نسبة الرسوم المحصلة</span>
                        <span class="text-primary">80%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: 80%"></div>
                    </div>
                </div>

                <div class="quick-stat">
                    <div class="d-flex justify-content-between mb-1">
                        <span>رضا المرضى</span>
                        <span class="text-warning">90%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 90%"></div>
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
