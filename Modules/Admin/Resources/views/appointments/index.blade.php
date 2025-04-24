@extends('admin::layouts.master')

@section('title', 'إدارة المواعيد')

@section('actions')
    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> موعد جديد
    </a>
@endsection

@section('content')
<div class="row mb-4">
    <!-- الإحصائيات المالية -->
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">إجمالي الرسوم</h6>
                <h3 class="mb-0">{{ number_format($stats['total_fees'], 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">الرسوم المدفوعة</h6>
                <h3 class="mb-0">{{ number_format($stats['paid_fees'], 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">الرسوم المستحقة</h6>
                <h3 class="mb-0">{{ number_format($stats['unpaid_fees'], 2) }} ج.م</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">عدد المواعيد</h6>
                <h3 class="mb-0">{{ $stats['total_appointments'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <!-- أدوات التصفية -->
                <form action="{{ route('admin.appointments.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="date_filter" class="form-select">
                                <option value="">كل المواعيد</option>
                                <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>اليوم</option>
                                <option value="upcoming" {{ request('date_filter') === 'upcoming' ? 'selected' : '' }}>المواعيد القادمة</option>
                                <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>الأسبوع القادم</option>
                                <option value="custom" {{ request('date_filter') === 'custom' ? 'selected' : '' }}>تاريخ محدد</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">كل الحالات</option>
                                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="doctor_id" class="form-select">
                                <option value="">كل الأطباء</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">تصفية</button>
                        </div>
                    </div>

                    <div class="row mt-3 custom-date-inputs" style="display: none;">
                        <div class="col-md-6">
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="من تاريخ">
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="إلى تاريخ">
                        </div>
                    </div>
                </form>

                <!-- جدول المواعيد -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المريض</th>
                                <th>الطبيب</th>
                                <th>الموعد</th>
                                <th>الرسوم</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                                <tr @if($appointment->is_important) class="table-warning" @endif>
                                    <td>{{ $appointment->id }}</td>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ $appointment->doctor->name }}</td>
                                    <td>{{ $appointment->scheduled_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        {{ number_format($appointment->fees, 2) }} ج.م
                                        @if($appointment->is_paid)
                                            <span class="badge bg-success">مدفوع</span>
                                        @else
                                            <span class="badge bg-warning">غير مدفوع</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status_color }}">
                                            {{ $appointment->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.appointments.show', $appointment) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.appointments.edit', $appointment) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.appointments.destroy', $appointment) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger delete-confirmation">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد مواعيد</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $appointments->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- المواعيد القادمة والتنبيهات -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-event"></i>
                    المواعيد القادمة
                </h5>
            </div>
            <div class="card-body">
                @php
                    $upcomingAppointments = \App\Models\Appointment::upcoming()->take(5)->get();
                @endphp
                @forelse($upcomingAppointments as $upcoming)
                    <div class="d-flex align-items-center mb-3 p-2 border rounded {{ $upcoming->is_important ? 'bg-warning bg-opacity-10' : '' }}">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $upcoming->patient->name }}</h6>
                            <small class="text-muted">
                                {{ $upcoming->scheduled_at->format('Y-m-d H:i') }}
                                @if($upcoming->is_important)
                                    <span class="badge bg-warning">مهم</span>
                                @endif
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('admin.appointments.show', $upcoming) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted my-3">لا توجد مواعيد قادمة</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bell"></i>
                    التنبيهات
                </h5>
            </div>
            <div class="card-body">
                @php
                    $importantAppointments = \App\Models\Appointment::where('is_important', true)
                        ->where('status', 'scheduled')
                        ->orderBy('scheduled_at')
                        ->take(5)
                        ->get();
                @endphp
                @forelse($importantAppointments as $important)
                    <div class="alert alert-warning mb-3">
                        <h6 class="mb-1">{{ $important->patient->name }}</h6>
                        <p class="mb-0 small">
                            {{ $important->scheduled_at->format('Y-m-d H:i') }}
                            <br>
                            {{ $important->notes }}
                        </p>
                    </div>
                @empty
                    <p class="text-center text-muted my-3">لا توجد تنبيهات مهمة</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إظهار/إخفاء حقول التاريخ المخصص
    const dateFilter = document.querySelector('select[name="date_filter"]');
    const customDateInputs = document.querySelector('.custom-date-inputs');

    function toggleCustomDateInputs() {
        customDateInputs.style.display = dateFilter.value === 'custom' ? 'flex' : 'none';
    }

    dateFilter.addEventListener('change', toggleCustomDateInputs);
    toggleCustomDateInputs();

    // تأكيد الحذف
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا الموعد؟')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush

@endsection
