@extends('layouts.admin')

@section('title', 'إدارة المواعيد')

@section('header_icon')
    <i class="bi bi-calendar2-check text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item active">المواعيد</li>
@endsection

@section('actions')
    <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> إضافة
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body position-relative">
            <!-- Enhanced Filters -->
            <div class="filters mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="dateFilter" class="form-label">تاريخ الموعد</label>
                        <select name="date_filter" class="form-select select2" id="dateFilter">
                            <option value="">الكل</option>
                            <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>اليوم</option>
                            <option value="upcoming" {{ request('date_filter') === 'upcoming' ? 'selected' : '' }}>المواعيد القادمة</option>
                            <option value="past" {{ request('date_filter') === 'past' ? 'selected' : '' }}>المواعيد السابقة</option>
                            <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                            <option value="month" {{ request('date_filter') === 'month' ? 'selected' : '' }}>هذا الشهر</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">حالة الموعد</label>
                        <select name="status_filter" class="form-select select2" id="statusFilter">
                            <option value="">الكل</option>
                            <option value="pending" {{ request('status_filter') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="confirmed" {{ request('status_filter') === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                            <option value="completed" {{ request('status_filter') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ request('status_filter') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="doctorFilter" class="form-label">الطبيب المعالج</label>
                        <select name="doctor_filter" class="form-select select2" id="doctorFilter">
                            <option value="">الكل</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ request('doctor_filter') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="searchInput" class="form-label">اسم المريض</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search"
                                   class="form-control"
                                   id="searchInput"
                                   name="search"
                                   placeholder="اسم المريض..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100 d-flex align-items-center" id="applyFilters">
                            <i class="bi bi-funnel-fill me-1"></i>
                            تطبيق
                        </button>
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
                            <tr>
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
                                            <img src="{{ Storage::url($appointment->doctor->image) }}" class="rounded-circle me-2"
                                                width="32" height="32"
                                                onerror="this.onerror=null;this.src='{{ asset('images/default-doctor.png') }}';"
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
                                        <i class="bi bi-clock text-success mx-2"></i>
                                        {{ $appointment->scheduled_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td>{{ number_format($appointment->fees) }} ج.م</td>
                                <td>
                                    @if($appointment->is_paid)
                                        <span class="status-badge active">
                                            <i class="bi bi-check-circle-fill"></i>
                                            مدفوع
                                        </span>
                                    @else
                                        <span class="status-badge inactive">
                                            <i class="bi bi-x-circle-fill"></i>
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
                                        <button type="button" class="btn-action btn-delete" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $appointment->id }}" data-bs-tooltip="tooltip"
                                            data-bs-placement="top" title="حذف">
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
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">إلغاء</button>
                                                    <form action="{{ route('appointments.destroy', $appointment) }}"
                                                        method="POST" class="d-inline">
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
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Select2
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });

                // الحصول على عناصر الفلتر
                const dateFilter = document.getElementById('dateFilter');
                const statusFilter = document.getElementById('statusFilter');
                const doctorFilter = document.getElementById('doctorFilter');
                const searchInput = document.getElementById('searchInput');
                const applyFiltersBtn = document.getElementById('applyFilters');

                // دالة لتحديث الفلاتر
                function updateFilters() {
                    const params = new URLSearchParams(window.location.search);

                    // تحديث قيم الفلاتر
                    if (dateFilter?.value) params.set('date_filter', dateFilter.value);
                    if (statusFilter?.value) params.set('status_filter', statusFilter.value);
                    if (doctorFilter?.value) params.set('doctor_filter', doctorFilter.value);
                    if (searchInput?.value) params.set('search', searchInput.value);

                    // إزالة القيم الفارغة
                    for (const [key, value] of params.entries()) {
                        if (!value) params.delete(key);
                    }

                    // تحديث الرابط مع الفلاتر الجديدة
                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                }

                // إضافة مستمع الحدث لزر تطبيق الفلاتر
                applyFiltersBtn.addEventListener('click', updateFilters);

                // إضافة مستمع حدث للبحث عند الضغط على Enter
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        updateFilters();
                    }
                });

                // تفعيل tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>

        <style>
            .form-label {
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
                color: var(--bs-gray-700);
            }

            .filters {
                background: var(--bs-gray-100);
                border-radius: 0.5rem;
                padding: 1.25rem;
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
