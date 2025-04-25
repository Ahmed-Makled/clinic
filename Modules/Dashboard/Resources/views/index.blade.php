@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-4 fw-bold text-dark">لوحة التحكم</h1>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Doctors Card -->
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 rounded-4 overflow-hidden bg-primary bg-opacity-10">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-0 text-dark">الأطباء</h6>
                            <h3 class="display-6 fw-bold mb-0 text-primary">{{ $stats['doctors'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-25 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="bi bi-person-badge fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patients Card -->
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 rounded-4 overflow-hidden bg-success bg-opacity-10">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-0 text-dark">المرضى</h6>
                            <h3 class="display-6 fw-bold mb-0 text-success">{{ $stats['patients'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-25 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="bi bi-people fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Card -->
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 rounded-4 overflow-hidden bg-warning bg-opacity-10">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-0 text-dark">المواعيد</h6>
                            <h3 class="display-6 fw-bold mb-0 text-warning">{{ $stats['appointments'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-25 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="bi bi-calendar-check fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments Card -->
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 rounded-4 overflow-hidden bg-info bg-opacity-10">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-0 text-dark">مواعيد اليوم</h6>
                            <h3 class="display-6 fw-bold mb-0 text-info">{{ $stats['today_appointments'] ?? 0 }}</h3>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-25 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="bi bi-calendar2-day fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Fees Card -->
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100 border-0 rounded-4 overflow-hidden bg-warning bg-opacity-10">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-0 text-dark">إجمالي الرسوم</h6>
                            <h3 class="display-6 fw-bold mb-0 text-warning">{{ number_format($stats['total_fees'], 2) }} ج.م</h3>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-25 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="bi bi-currency-dollar fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Fees Card -->
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100 border-0 rounded-4 overflow-hidden bg-success bg-opacity-10">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-0 text-dark">الرسوم المدفوعة</h6>
                            <h3 class="display-6 fw-bold mb-0 text-success">{{ number_format($stats['paid_fees'], 2) }} ج.م</h6>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-25 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="bi bi-cash-stack fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unpaid Fees Card -->
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100 border-0 rounded-4 overflow-hidden bg-danger bg-opacity-10">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="card-title mb-0 text-dark">الرسوم المستحقة</h6>
                            <h3 class="display-6 fw-bold mb-0 text-danger">{{ number_format($stats['unpaid_fees'], 2) }} ج.م</h3>
                        </div>
                        <div class="rounded-circle bg-danger bg-opacity-25 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="bi bi-cash fs-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-5">
        <!-- Appointments Chart -->
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4 fw-bold">إحصائيات المواعيد</h5>
                    <div style="height: 300px">
                        <canvas id="appointmentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Specialties Chart -->
        <div class="col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4 fw-bold">توزيع التخصصات</h5>
                    <div style="height: 300px">
                        <canvas id="specialtiesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4 fw-bold">آخر النشاطات</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">المريض</th>
                                    <th scope="col">الطبيب</th>
                                    <th scope="col">التاريخ</th>
                                    <th scope="col">الحالة</th>
                                    <th scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity->patient->name }}</td>
                                    <td>{{ $activity->doctor->name }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar3 me-2 text-muted"></i>
                                            {{ $activity->scheduled_at->format('Y-m-d H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($activity->status === 'completed')
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="bi bi-check-circle me-1"></i>مكتمل
                                            </span>
                                        @elseif($activity->status === 'scheduled')
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                <i class="bi bi-calendar-check me-1"></i>مجدول
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="bi bi-x-circle me-1"></i>ملغي
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('appointments.show', $activity) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           data-bs-title="عرض التفاصيل">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Appointments Chart
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appointmentsCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels'] ?? []),
            datasets: [{
                label: 'المواعيد',
                data: @json($chartData['appointments'] ?? []),
                borderColor: '#3b82f6',
                backgroundColor: '#3b82f620',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            return `المواعيد: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: '#e2e8f0',
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 1,
                        padding: 10,
                        color: '#64748b'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        padding: 10,
                        color: '#64748b'
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
            labels: @json($chartData['specialtyLabels'] ?? []),
            datasets: [{
                data: @json($chartData['specialtyCounts'] ?? []),
                backgroundColor: [
                    '#3b82f6',  // blue
                    '#22c55e',  // green
                    '#f59e0b',  // yellow
                    '#6366f1',  // indigo
                    '#ec4899'   // pink
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed} طبيب`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-4px);
}

.badge {
    font-weight: 500;
}

.table th {
    font-weight: 600;
}

.table > :not(:first-child) {
    border-top: none;
}
</style>
@endpush

@endsection
