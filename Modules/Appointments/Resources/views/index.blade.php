@extends('layouts.admin')

@section('title', 'إدارة المواعيد')

@section('actions')
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> موعد جديد
    </a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="card-body">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3 class="stat-value">{{ $stats['total_appointments'] }}</h3>
                <p class="stat-label">إجمالي المواعيد</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <div class="card-body">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <h3 class="stat-value">{{ number_format($stats['paid_fees']) }} ج.م</h3>
                <p class="stat-label">الرسوم المحصلة</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="card-body">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3 class="stat-value">{{ number_format($stats['unpaid_fees']) }} ج.م</h3>
                <p class="stat-label">الرسوم المستحقة</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <div class="card-body">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <h3 class="stat-value">{{ $stats['today_appointments'] }}</h3>
                <p class="stat-label">مواعيد اليوم</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body position-relative">
        <!-- Enhanced Filters -->
        <div class="filters mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="date_filter" class="form-select select2" id="dateFilter">
                        <option value="">كل المواعيد</option>
                        <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="upcoming" {{ request('date_filter') === 'upcoming' ? 'selected' : '' }}>المواعيد القادمة</option>
                        <option value="past" {{ request('date_filter') === 'past' ? 'selected' : '' }}>المواعيد السابقة</option>
                        <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                        <option value="month" {{ request('date_filter') === 'month' ? 'selected' : '' }}>هذا الشهر</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status_filter" class="form-select select2" id="statusFilter">
                        <option value="">كل الحالات</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="doctor_filter" class="form-select select2" id="doctorFilter">
                        <option value="">كل الأطباء</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                     </span>
                        <input type="search"
                               class="form-control"
                               id="searchInput"
                               placeholder="ابحث عن مريض..."
                               value="{{ request('search') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">المريض</th>
                        <th scope="col">الطبيب</th>
                        <th scope="col">التاريخ والوقت</th>
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
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width: 32px; height: 32px">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                    <div>{{ $appointment->patient->name }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($appointment->doctor->image)
                                        <img src="{{ Storage::url($appointment->doctor->image) }}"
                                             class="rounded-circle me-2"
                                             width="32"
                                             height="32"
                                             alt="{{ $appointment->doctor->name }}">
                                    @else
                                        <div class="bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px">
                                            <i class="bi bi-person-badge text-primary"></i>
                                        </div>
                                    @endif
                                    <div>{{ $appointment->doctor->name }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar2 text-primary me-2"></i>
                                    {{ $appointment->scheduled_at->format('Y-m-d') }}
                                    <br>
                                    <i class="bi bi-clock text-success me-2"></i>
                                    {{ $appointment->scheduled_at->format('h:i A') }}
                                </div>
                            </td>
                            <td>{{ number_format($appointment->fees) }} ج.م</td>
                            <td>
                                @if($appointment->is_paid)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        مدفوع
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        غير مدفوع
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $appointment->status_color }}">
                                    {{ $appointment->status_text }}
                                </span>
                                @if($appointment->is_important)
                                    <span class="badge bg-danger text-white">
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn-action btn-view"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="عرض">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn-action btn-edit"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $appointment->id }}"
                                            data-bs-tooltip="tooltip"
                                            data-bs-placement="top"
                                            title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $appointment->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تأكيد الحذف</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>هل أنت متأكد من حذف هذا الموعد؟</p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    لا يمكن التراجع عن هذا الإجراء.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash me-2"></i>
                                                        حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-calendar-x display-6 d-block mb-3 opacity-50"></i>
                                    <p class="h5 opacity-75">لا توجد مواعيد</p>
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
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Filter handling
    const filterForm = document.createElement('form');
    filterForm.method = 'GET';

    ['dateFilter', 'statusFilter', 'doctorFilter'].forEach(filterId => {
        const filter = document.getElementById(filterId);
        if (filter) {
            filter.addEventListener('change', () => filterForm.submit());
        }
    });

    // Search handling
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.filters {
    background: var(--background-color);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin: -1rem -1rem 1rem -1rem;
}

.table th {
    white-space: nowrap;
}

.badge {
    font-weight: 500;
}

.badge i {
    font-size: 0.85em;
}

/* Enhanced action buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: var(--secondary-color);
    transition: all var(--transition-speed) ease;
}

.btn-action:hover {
    transform: translateY(-2px);
}

.btn-action.btn-view:hover {
    background: var(--info-bg-subtle);
    color: var(--info-color);
}

.btn-action.btn-edit:hover {
    background: var(--primary-bg-subtle);
    color: var(--primary-color);
}

.btn-action.btn-delete:hover {
    background: var(--danger-bg-subtle);
    color: var(--danger-color);
}

/* Modal enhancements */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 1rem 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.table-warning {
    --bs-table-bg: var(--warning-bg-subtle);
    --bs-table-color: inherit;
}

/* Responsive enhancements */
@media (max-width: 768px) {
    .filters .row {
        row-gap: 1rem;
    }

    .table-responsive {
        margin: 0 -1rem;
        padding: 0 1rem;
        width: calc(100% + 2rem);
    }

    .action-buttons {
        flex-wrap: nowrap;
    }
}
</style>
@endpush

@endsection
