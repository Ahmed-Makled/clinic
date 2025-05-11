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

            <div class="d-flex justify-content-between align-items-center mt-4 pagination-wrapper">
                <div class="text-muted small">
                    إجمالي النتائج: {{ $appointments->total() }}
                </div>
                @if($appointments->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($appointments->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $appointments->previousPageUrl() }}" rel="prev">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($appointments->getUrlRange(max($appointments->currentPage() - 2, 1), min($appointments->currentPage() + 2, $appointments->lastPage())) as $page => $url)
                                @if ($page == $appointments->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($appointments->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $appointments->nextPageUrl() }}" rel="next">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
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
            padding-top: 1rem;
            border-top: 1px solid var(--bs-gray-200);
        }

        .pagination {
            margin: 0;
        }

        .pagination .page-item {
            margin: 0 2px;
        }

        .pagination .page-link {
            border-radius: 4px;
            border: 1px solid var(--bs-gray-300);
            color: var(--bs-gray-700);
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
            box-shadow: 0 2px 4px rgba(var(--bs-primary-rgb), 0.2);
        }

        .pagination .page-link:hover:not(.disabled) {
            background-color: var(--bs-gray-100);
            border-color: var(--bs-gray-400);
            color: var(--bs-primary);
        }

        .pagination .page-item.disabled .page-link {
            background-color: var(--bs-gray-100);
            border-color: var(--bs-gray-200);
            color: var(--bs-gray-400);
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
