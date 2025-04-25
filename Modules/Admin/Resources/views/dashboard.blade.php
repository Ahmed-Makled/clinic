@extends('layouts.admin')

@section('content')
<div class="container" style="background-color: #F7F8FA; font-family: 'Tajawal', sans-serif;">
    <h1 class="mb-4 fw-bold" style="color: #2D3748;">لوحة التحكم</h1>

    <!-- إحصائيات سريعة -->
    <div class="row mb-5">
        <!-- بطاقة الأطباء -->
        <div class="col-md-3 mb-4">
            <div class="card h-100" style="background-color: #DCEEFB; border: none; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="card-title mb-0" style="color: #2D3748; font-size: 1.1rem;">الأطباء</p>
                            <h3 class="display-6 fw-bold mb-0" style="color: #3182CE;">{{ $stats['doctors'] }}</h3>
                        </div>
                        <div class="rounded-circle p-3 d-flex align-items-center " style="background-color: #BEE3F8; width: 60px; height: 60px;">
                            <i class="bi bi-person-badge fs-4" style="color: #3182CE; line-height: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة المرضى -->
        <div class="col-md-3 mb-4">
            <div class="card h-100" style="background-color: #E3F9E5; border: none; border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="card-title mb-0" style="color: #2D3748; font-size: 1.1rem;">المرضى</p>
                            <h3 class="display-6 fw-bold mb-0" style="color: #38A169;">{{ $stats['patients'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #C6F6D5;width: 60px; height: 60px;">
                            <i class="bi bi-people fs-4" style="color: #38A169;line-height: 1.5rem"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة المواعيد -->
        <div class="col-md-3 mb-4">
            <div class="card h-100" style="background-color: #FFF4E5; border: none; border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="card-title mb-0" style="color: #2D3748; font-size: 1.1rem;">المواعيد</p>
                            <h3 class="display-6 fw-bold mb-0" style="color: #DD6B20;">{{ $stats['appointments'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #FEEBC8;width: 60px; height: 60px;">
                            <i class="bi bi-calendar-check fs-4" style="color: #DD6B20;line-height: 1.5rem"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة المواعيد اليوم -->
        <div class="col-md-3 mb-4">
            <div class="card h-100" style="background-color: #E9E8FF; border: none; border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="card-title mb-0" style="color: #2D3748; font-size: 1.1rem;">مواعيد اليوم</p>
                            <h3 class="display-6 fw-bold mb-0" style="color: #6B46C1;">{{ $stats['today_appointments'] ?? 0 }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D5D4FF;width: 60px; height: 60px;">
                            <i class="bi bi-calendar2-day fs-4" style="color: #6B46C1;line-height: 1.5rem"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة إجمالي الرسوم -->
        <div class="col-md-3 mb-4">
            <div class="card h-100" style="background-color: #FEFCE8; border: none; border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="card-title mb-0" style="color: #2D3748; font-size: 1.1rem;">إجمالي الرسوم</p>
                            <h3 class="display-6 fw-bold mb-0" style="color: #B45309;">{{ number_format($stats['total_fees'], 2) }} ج.م</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #FEF3C7; width: 60px; height: 60px;">
                            <i class="bi bi-currency-dollar fs-4" style="color: #B45309;line-height: 1.5rem"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة الرسوم المدفوعة -->
        <div class="col-md-3 mb-4">
            <div class="card h-100" style="background-color: #ECFDF5; border: none; border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="card-title mb-0" style="color: #2D3748; font-size: 1.1rem;">الرسوم المدفوعة</p>
                            <h3 class="display-6 fw-bold mb-0" style="color: #047857;">{{ number_format($stats['paid_fees'], 2) }} ج.م</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D1FAE5; width: 60px; height: 60px;">
                            <i class="bi bi-cash-stack fs-4" style="color: #047857;line-height: 1.5rem"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة الرسوم المستحقة -->
        <div class="col-md-3 mb-4">
            <div class="card h-100" style="background-color: #FEF2F2; border: none; border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="card-title mb-0" style="color: #2D3748; font-size: 1.1rem;">الرسوم المستحقة</p>
                            <h3 class="display-6 fw-bold mb-0" style="color: #DC2626;">{{ number_format($stats['unpaid_fees'], 2) }} ج.م</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #FEE2E2; width: 60px; height: 60px;">
                            <i class="bi bi-cash fs-4" style="color: #DC2626;line-height: 1.5rem"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الرسوم البيانية -->
    <div class="row mb-5">
        <div class="col-md-8 mb-4">
            <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4" style="color: #2D3748;">إحصائيات المواعيد</h5>
                    <canvas id="appointmentsChart" class="chart-container"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4" style="color: #2D3748;">توزيع التخصصات</h5>
                    <canvas id="specialtiesChart" class="chart-container"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- آخر النشاطات -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4" style="color: #2D3748;">آخر النشاطات</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>المريض</th>
                                    <th>الطبيب</th>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity->patient->name }}</td>
                                    <td>{{ $activity->doctor->name }}</td>
                                    <td>{{ $activity->scheduled_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $activity->status === 'completed' ? 'success' : ($activity->status === 'scheduled' ? 'primary' : 'danger') }}">
                                            {{ $activity->status === 'completed' ? 'مكتمل' : ($activity->status === 'scheduled' ? 'مجدول' : 'ملغي') }}
                                        </span>
                                    </td>
                                    <td></td>
                                        <a href="{{ route('appointments.show', $activity) }}" class="btn btn-sm btn-outline-primary"></a>
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
document.addEventListener('DOMContentLoaded', function() {}
    // إحصائيات المواعيد
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appointmentsCtx, {
        type: 'line',
        data: {}
            labels: @json($chartData['labels'] ?? []),
            datasets: [{}
                label: 'المواعيد',
                data: @json($chartData['appointments'] ?? []),
                borderColor: '#3182CE',
                tension: 0.1,
                fill: false
            }]
        },
        options: {}
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
                    ticks: {}
                        stepSize: 1
                    }
                }
            }
        }
    });

    // توزيع التخصصات
    const specialtiesCtx = document.getElementById('specialtiesChart').getContext('2d');
    new Chart(specialtiesCtx, {
        type: 'doughnut',
        data: {}
            labels: @json($chartData['specialtyLabels'] ?? []),
            datasets: [{}
                data: @json($chartData['specialtyCounts'] ?? []),
                backgroundColor: [
                    '#3182CE',
                    '#38A169',
                    '#DD6B20',
                    '#6B46C1',
                    '#E53E3E'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {}
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush

@endsection
