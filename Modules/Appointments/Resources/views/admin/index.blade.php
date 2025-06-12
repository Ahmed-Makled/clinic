@extends('layouts.admin')

@section('title', 'إدارة الحجوزات')

@section('header_icon')
    <i class="bi bi-calendar2-check text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item active">الحجوزات</li>
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
                        <label for="dateFilter" class="form-label">تاريخ الحجز</label>
                        <select name="date_filter" class="form-select select2" id="dateFilter">
                            <option value="">الكل</option>
                            <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>اليوم</option>
                            <option value="upcoming" {{ request('date_filter') === 'upcoming' ? 'selected' : '' }}>الحجوزات القادمة</option>
                            <option value="past" {{ request('date_filter') === 'past' ? 'selected' : '' }}>الحجوزات السابقة</option>
                            <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                            <option value="month" {{ request('date_filter') === 'month' ? 'selected' : '' }}>هذا الشهر</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">حالة الحجز</label>
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
                        <button type="button" class="btn btn-primary d-flex align-items-center" id="applyFilters">
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
                            <th scope="col">رقم الحجز</th>
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
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="appointment-number">#{{ $appointment->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <div>{{ $appointment->patient->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($appointment->doctor->image)
                                            <img src="{{ $appointment->doctor->image_url }}" alt="{{ $appointment->doctor->name }}" class="doctor-avatar">

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
                                        <span class="badge bg-danger text-white important">
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
                                                    <p>هل أنت متأكد من حذف هذا الحجز؟</p>
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
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-calendar-x display-6 d-block mb-3 opacity-50"></i>
                                        <p class="h5 opacity-75">لا توجد حجوزات</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($appointments->hasPages())
                <div class="pagination-wrapper mt-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="pagination-info">
                                <span class="text-muted">
                                    عرض {{ $appointments->firstItem() }} إلى {{ $appointments->lastItem() }} من أصل {{ $appointments->total() }} نتيجة
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                <ul class="pagination custom-pagination mb-0">
                                    {{-- First Page Link --}}
                                    @if (!$appointments->onFirstPage())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $appointments->url(1) }}" title="الصفحة الأولى">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Previous Page Link --}}
                                    @if ($appointments->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $appointments->previousPageUrl() }}" title="الصفحة السابقة">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $start = max($appointments->currentPage() - 2, 1);
                                        $end = min($appointments->currentPage() + 2, $appointments->lastPage());

                                        $showStartDots = $start > 2;
                                        $showEndDots = $end < $appointments->lastPage() - 1;
                                    @endphp

                                    {{-- Always show first page if not in range --}}
                                    @if ($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $appointments->url(1) }}">1</a>
                                        </li>
                                        @if ($showStartDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $appointments->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $appointments->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Always show last page if not in range --}}
                                    @if ($end < $appointments->lastPage())
                                        @if ($showEndDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $appointments->url($appointments->lastPage()) }}">{{ $appointments->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($appointments->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $appointments->nextPageUrl() }}" title="الصفحة التالية">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-left"></i>
                                            </span>
                                        </li>
                                    @endif

                                    {{-- Last Page Link --}}
                                    @if ($appointments->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $appointments->url($appointments->lastPage()) }}" title="الصفحة الأخيرة">
                                                <i class="bi bi-chevron-double-left"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            @else
                <div class="pagination-wrapper mt-4">
                    <div class="text-center">
                        <span class="text-muted">إجمالي النتائج: {{ $appointments->total() }}</span>
                    </div>
                </div>
            @endif
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

                    // فحص وإضافة الفلاتر فقط إذا كانت لها قيمة
                    if (dateFilter?.value?.trim()) {
                        params.set('date_filter', dateFilter.value.trim());
                    } else {
                        params.delete('date_filter');
                    }

                    if (statusFilter?.value?.trim()) {
                        params.set('status_filter', statusFilter.value.trim());
                    } else {
                        params.delete('status_filter');
                    }

                    if (doctorFilter?.value?.trim()) {
                        params.set('doctor_filter', doctorFilter.value.trim());
                    } else {
                        params.delete('doctor_filter');
                    }

                    if (searchInput?.value?.trim()) {
                        params.set('search', searchInput.value.trim());
                    } else {
                        params.delete('search');
                    }

                    // تحديث الرابط مع الفلاتر
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

            .appointment-number {
                font-family: monospace;
                font-weight: 600;
                color: var(--bs-primary);
                background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
                padding: 0.25rem 0.75rem;
                border-radius: 50px;
                font-size: 0.875rem;
                border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
            }

            .doctor-avatar {
                width: 32px;
                height: 32px;
                border-radius: 12px;
                object-fit: cover;
                margin-right: 0.75rem;
            }
        </style>
    @endpush

    @push('styles')
    <style>
        .pagination-wrapper {
            padding-top: 1.5rem;
            border-top: 1px solid var(--bs-gray-200);
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 0 0 0.5rem 0.5rem;
            padding: 1.5rem;
            margin: 0 -1.5rem -1.5rem -1.5rem;
        }

        .pagination-info {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .custom-pagination {
            margin: 0;
            gap: 0.25rem;
        }

        .custom-pagination .page-item {
            margin: 0;
        }

        .custom-pagination .page-link {
            border: 1px solid var(--bs-gray-300);
            color: var(--bs-gray-700);
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            background: white;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .custom-pagination .page-item:first-child .page-link,
        .custom-pagination .page-item:last-child .page-link {
            min-width: 2.5rem;
        }

        .custom-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #0056b3 100%);
            border-color: var(--bs-primary);
            color: white;
            box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.3);
            transform: translateY(-1px);
        }

        .custom-pagination .page-link:hover:not(.disabled) {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.2);
        }

        .custom-pagination .page-item.disabled .page-link {
            background-color: var(--bs-gray-100);
            border-color: var(--bs-gray-200);
            color: var(--bs-gray-400);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .custom-pagination .page-link.dots {
            border: none;
            background: transparent;
            box-shadow: none;
            color: var(--bs-gray-500);
            cursor: default;
            font-weight: 600;
            letter-spacing: 0.1em;
        }

        .custom-pagination .page-item.disabled .page-link.dots {
            background: transparent;
            border: none;
        }

        .custom-pagination .page-link i {
            font-size: 0.75rem;
            line-height: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .pagination-wrapper .row {
                flex-direction: column-reverse;
                gap: 1rem;
            }

            .pagination-wrapper .col-md-6 {
                text-align: center;
            }

            .custom-pagination {
                justify-content: center;
                flex-wrap: wrap;
            }

            .custom-pagination .page-link {
                padding: 0.375rem 0.5rem;
                min-width: 2rem;
                height: 2rem;
                font-size: 0.8rem;
            }

            .pagination-info {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .custom-pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
                display: none;
            }
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%) !important;
            color: #9333ea;
        }
        .badge.bg-danger {
            background-color: var(--danger-bg-subtle) !important;
            color: var(--danger-color);
        }
        .badge.bg-danger.important {
            margin-inline-start: 4px;
            background-color: rgba(var(--bs-danger-rgb),var(--bs-bg-opacity))!important ;
            color: var(--danger-color);
        }
        .badge.bg-success {
            background-color: var(--success-bg-subtle) !important;
            color: var(--success-color);
        }

        .badge.bg-warning {
            background-color: var(--warning-bg-subtle) !important;
            color: var(--warning-color);
        }

        .badge.bg-danger.notifications-count {
            background-color: var(--danger-bg-subtle) !important;
            color: var(--danger-color);
        }

        .appointment-number {
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%);
            color: #9333ea;
            border: 1px solid rgba(147, 51, 234, 0.1);
        }
    </style>
@endpush

@endsection
