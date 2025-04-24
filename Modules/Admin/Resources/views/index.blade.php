@extends('admin::layouts.master')

@section('title', 'لوحة التحكم')

@section('content')
<div class="row g-4">
    <!-- إحصائيات سريعة -->
    <div class="col-md-3">
        <div class="stats-card card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">الأطباء</h6>
                <h2 class="mb-0">{{ $stats['doctors_count'] }}</h2>
                <small class="text-white-50">إجمالي عدد الأطباء</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">المرضى</h6>
                <h2 class="mb-0">{{ $stats['patients_count'] }}</h2>
                <small class="text-white-50">إجمالي عدد المرضى</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">المواعيد اليوم</h6>
                <h2 class="mb-0">{{ $stats['today_appointments'] }}</h2>
                <small class="text-white-50">عدد المواعيد اليوم</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">التخصصات</h6>
                <h2 class="mb-0">{{ $stats['specialties_count'] }}</h2>
                <small class="text-white-50">عدد التخصصات</small>
            </div>
        </div>
    </div>

    <!-- الرسم البياني -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">إحصائيات المواعيد</h5>
                <canvas id="appointmentsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- آخر المواعيد -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">آخر المواعيد</h5>
                <div class="list-group list-group-flush">
                    @foreach($latestAppointments as $appointment)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $appointment->patient->name }}</h6>
                            <small>{{ $appointment->formatted_date }}</small>
                        </div>
                        <p class="mb-1">مع د. {{ $appointment->doctor->name }}</p>
                        <small class="text-muted">{{ $appointment->status }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // إعداد الرسم البياني
    const ctx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart['labels']) !!},
            datasets: [{
                label: 'المواعيد',
                data: {!! json_encode($chart['data']) !!},
                borderColor: '#3498db',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
