@extends('layouts.admin')

@section('title', 'إدارة المواعيد')

@section('actions')
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> موعد جديد
    </a>
@endsection

@section('content')
<div class="modern-table-container">
    <div class="table-controls">
        <div class="row g-3">
            <div class="col-md-3">
                <select name="date_filter" class="filter-select" id="dateFilter">
                    <option value="">كل المواعيد</option>
                    <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>اليوم</option>
                    <option value="upcoming" {{ request('date_filter') === 'upcoming' ? 'selected' : '' }}>المواعيد القادمة</option>
                    <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>الأسبوع القادم</option>
                    <option value="custom" {{ request('date_filter') === 'custom' ? 'selected' : '' }}>تاريخ محدد</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="doctor_id" class="filter-select" id="doctorFilter">
                    <option value="">كل الأطباء</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="filter-select" id="statusFilter">
                    <option value="">كل الحالات</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary w-100" id="applyFilters">
                    <i class="bi bi-funnel"></i> تصفية
                </button>
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
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المريض</th>
                    <th>الطبيب</th>
                    <th>التاريخ</th>
                    <th>الرسوم</th>
                    <th>حالة الدفع</th>
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
                        <td>
                            <div>{{ $appointment->scheduled_at->format('Y-m-d') }}</div>
                            <small class="text-muted">{{ $appointment->scheduled_at->format('h:i A') }}</small>
                        </td>
                        <td>{{ number_format($appointment->fees, 2) }} ج.م</td>
                        <td>
                            @if($appointment->is_paid)
                                <span class="status-badge status-badge-active">مدفوع</span>
                            @else
                                <span class="status-badge status-badge-pending">غير مدفوع</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge bg-{{ $appointment->status_color }}">
                                {{ $appointment->status_text }}
                            </span>
                            @if($appointment->is_important)
                                <span class="status-badge status-badge-warning">
                                    <i class="bi bi-star-fill"></i>
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('appointments.show', $appointment) }}"
                                   class="btn-action btn-view">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('appointments.edit', $appointment) }}"
                                   class="btn-action btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('appointments.destroy', $appointment) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action btn-delete delete-confirmation">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-calendar-x empty-state-icon"></i>
                                <p class="empty-state-text">لا توجد مواعيد</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $appointments->links() }}
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

    // تطبيق الفلاتر
    document.getElementById('applyFilters').addEventListener('click', function() {
        const params = new URLSearchParams();

        // جمع قيم الفلاتر
        const dateFilterValue = dateFilter.value;
        const doctorValue = document.getElementById('doctorFilter').value;
        const statusValue = document.getElementById('statusFilter').value;

        if (dateFilterValue) params.append('date_filter', dateFilterValue);
        if (doctorValue) params.append('doctor_id', doctorValue);
        if (statusValue) params.append('status', statusValue);

        // إضافة تواريخ مخصصة إذا تم اختيارها
        if (dateFilterValue === 'custom') {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
        }

        // تحديث الصفحة مع الفلاتر
        window.location.search = params.toString();
    });

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
