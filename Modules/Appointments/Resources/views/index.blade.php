@extends('layouts.admin')

@section('title', 'إدارة المواعيد')

@section('actions')
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> موعد جديد
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="date_filter" class="form-select" id="dateFilter">
                        <option value="">كل المواعيد</option>
                        <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="upcoming" {{ request('date_filter') === 'upcoming' ? 'selected' : '' }}>المواعيد القادمة</option>
                        <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>الأسبوع القادم</option>
                        <option value="custom" {{ request('date_filter') === 'custom' ? 'selected' : '' }}>تاريخ محدد</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="doctor_id" class="form-select" id="doctorFilter">
                        <option value="">كل الأطباء</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" id="statusFilter">
                        <option value="">كل الحالات</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary w-100" id="applyFilters">
                        <i class="bi bi-funnel me-1"></i> تصفية
                    </button>
                </div>
            </div>

            <div class="row mt-3 custom-date-inputs" style="display: none;">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="من تاريخ">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="إلى تاريخ">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">المريض</th>
                        <th scope="col">الطبيب</th>
                        <th scope="col">التاريخ</th>
                        <th scope="col">الرسوم</th>
                        <th scope="col">حالة الدفع</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">الإجراءات</th>
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
                                    <span class="badge bg-success">مدفوع</span>
                                @else
                                    <span class="badge bg-warning text-dark">غير مدفوع</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $appointment->status_color }}">
                                    {{ $appointment->status_text }}
                                </span>
                                @if($appointment->is_important)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('appointments.edit', $appointment) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('appointments.destroy', $appointment) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger delete-confirmation">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-calendar-x display-6 d-block mb-3"></i>
                                    <p class="h5">لا توجد مواعيد</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('#doctorFilter, #statusFilter').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Toggle custom date inputs
    const dateFilter = document.querySelector('select[name="date_filter"]');
    const customDateInputs = document.querySelector('.custom-date-inputs');

    function toggleCustomDateInputs() {
        customDateInputs.style.display = dateFilter.value === 'custom' ? 'flex' : 'none';
    }

    dateFilter.addEventListener('change', toggleCustomDateInputs);
    toggleCustomDateInputs();

    // Apply filters
    document.getElementById('applyFilters').addEventListener('click', function() {
        const params = new URLSearchParams();

        const dateFilterValue = dateFilter.value;
        const doctorValue = document.getElementById('doctorFilter').value;
        const statusValue = document.getElementById('statusFilter').value;

        if (dateFilterValue) params.append('date_filter', dateFilterValue);
        if (doctorValue) params.append('doctor_id', doctorValue);
        if (statusValue) params.append('status', statusValue);

        if (dateFilterValue === 'custom') {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
        }

        window.location.search = params.toString();
    });

    // Delete confirmation using SweetAlert2
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف هذا الموعد نهائياً',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    .table th {
        font-weight: 600;
    }

    .btn-group > .btn {
        padding: 0.375rem 0.5rem;
    }

    .badge {
        font-weight: 500;
    }

    .table > :not(:first-child) {
        border-top: none;
    }

    .table tbody tr:hover {
        background-color: var(--bs-table-hover-bg);
    }
</style>
@endpush
@endsection
