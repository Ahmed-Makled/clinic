@extends('layouts.admin')
@section('title', 'إدارة الأطباء')

@section('header_icon')
    <i class="bi bi-person-badge text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item active">الأطباء</li>
@endsection

@section('actions')
    <a href="{{ route('doctors.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> إضافة
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body position-relative">
            <!-- Enhanced Filters -->
            <div class="filters mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="searchInput" class="form-label">اسم الدكتور</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" class="form-control" id="searchInput" name="search"
                                placeholder="ادخل اسم الدكتور..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="categoryFilter" class="form-label">التخصص</label>
                        <select class="form-select select2" id="categoryFilter" name="category_filter">
                            <option value="">الكل</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_filter') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">الحالة</label>
                        <select class="form-select select2" id="statusFilter" name="status_filter">
                            <option value="">الكل</option>
                            <option value="1" {{ request('status_filter') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('status_filter') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary d-flex align-items-center" id="applyFilters">
                            <i class="bi bi-funnel-fill me-1"></i>
                            تطبيق
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-nowrap bg-light">
                            <th scope="col" class="fw-medium ps-3">#</th>
                            <th scope="col" class="fw-medium">الطبيب</th>
                            <th scope="col" class="fw-medium">التخصصات</th>
                            <th scope="col" class="fw-medium">البريد الإلكتروني</th>
                            <th scope="col" class="fw-medium">رقم الهاتف</th>
                            <th scope="col" class="fw-medium">الحالة</th>
                            <th scope="col" class="fw-medium pe-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($doctors as $doctor)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            class="avatar-wrapper {{ $doctor->image ? '' : 'avatar-light' }}">
                                            @if($doctor->image)
                                                <img src="{{ Storage::url($doctor->image) }}" class="rounded-circle" width="32"
                                                    height="32" alt="{{ $doctor->name }}"
                                                    onerror="this.src='{{ asset('images/default-doctor.png') }}'">
                                            @else
                                                <i class="bi bi-person-badge"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-medium mb-0 fs-7">{{ $doctor->name }}</div>
                                            @if($doctor->degree)
                                                <small class="text-muted fs-8">{{ $doctor->degree }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($doctor->categories as $category)
                                            <span class="badge category-badge-{{ $category->id % 6 }} fs-8"
                                                data-id="{{ $category->id }}">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-envelope text-muted me-2 fs-8"></i>
                                        <span class="fs-7">{{ $doctor->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-phone text-muted me-2 fs-8"></i>
                                        <span class="fs-7">{{ $doctor->phone }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($doctor->status)
                                        <span class="status-badge active fs-8">
                                            <i class="bi bi-check-circle-fill"></i>
                                            نشط
                                        </span>
                                    @else
                                        <span class="status-badge inactive fs-8">
                                            <i class="bi bi-x-circle-fill"></i>
                                            غير نشط
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-3">
                                    <div class="action-buttons d-flex gap-1">
                                        <a href="{{ route('doctors.details', $doctor) }}" class="btn btn-light btn-sm px-2 py-1"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="عرض">
                                            <i class="bi bi-eye fs-7"></i>
                                        </a>
                                        <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-light btn-sm px-2 py-1"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="تعديل">
                                            <i class="bi bi-pencil fs-7"></i>
                                        </a>
                                        <form action="{{ route('doctors.destroy', $doctor) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm px-2 py-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="حذف">
                                                <i class="bi bi-trash fs-7"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="bi bi-people empty-state-icon opacity-50"></i>
                                        <p class="empty-state-text fs-7 mb-1">لا يوجد أطباء</p>
                                        <p class="empty-state-subtext fs-8 text-muted">قم بإضافة طبيب جديد من خلال الزر أعلاه
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <div class="d-flex justify-content-between align-items-center mt-4 pagination-wrapper">
                <div class="text-muted small">
                    إجمالي النتائج: {{ $doctors->total() }}
                </div>
                @if($doctors->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($doctors->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $doctors->previousPageUrl() }}" rel="prev">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($doctors->getUrlRange(max($doctors->currentPage() - 2, 1), min($doctors->currentPage() + 2, $doctors->lastPage())) as $page => $url)
                                @if ($page == $doctors->currentPage())
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
                            @if ($doctors->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $doctors->nextPageUrl() }}" rel="next">
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

            <!-- Loading Overlay -->
            <div class="loading-overlay d-none">
                <div class="loading-spinner"></div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal Template -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من حذف الطبيب "<span class="doctor-name fw-bold"></span>"؟</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        لا يمكن التراجع عن هذا الإجراء.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" action="" method="POST" class="d-inline">
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

    @push('styles')
        <style>
            /* Refined Typography */
            .fs-7 {
                font-size: 0.875rem;
            }

            .fs-8 {
                font-size: 0.8125rem;
            }

            /* Enhanced Table Styles */
            .table> :not(caption)>*>* {
                padding: 0.75rem 0.5rem;
            }

            .table thead th {
                font-size: 0.8125rem;
                color: #4B5563;
                font-weight: 500;
            }

            /* Refined Category Badges */
            .category-badge-0 {
                background-color: #E6F4FF;
                color: #0369A1;
            }

            .category-badge-1 {
                background-color: #ECFDF5;
                color: #047857;
            }

            .category-badge-2 {
                background-color: #EFF6FF;
                color: #1D4ED8;
            }

            .category-badge-3 {
                background-color: #FEF3C7;
                color: #B45309;
            }

            .category-badge-4 {
                background-color: #F3E8FF;
                color: #7E22CE;
            }

            .category-badge-5 {
                background-color: #FEE2E2;
                color: #B91C1C;
            }

            /* Status Badges */
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                padding: 0.25rem 0.5rem;
                border-radius: 1rem;
                font-weight: 500;
            }

            .status-badge.active {
                background-color: #ECFDF5;
                color: #047857;
            }

            .status-badge.inactive {
                background-color: #FEF2F2;
                color: #B91C1C;
            }

            /* Refined Action Buttons */
            .action-buttons .btn {
                transition: all 0.2s ease;
                border: none;
            }

            .action-buttons .btn:hover {
                background-color: #F3F4F6;
            }

            /* Empty State */
            .empty-state {
                padding: 2rem 1rem;
            }

            .empty-state-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
            }

            /* Loading Overlay */
            .loading-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }

            .loading-spinner {
                width: 2rem;
                height: 2rem;
                border: 2px solid #E5E7EB;
                border-top-color: #3B82F6;
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

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

            .avatar-wrapper {
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
            }

            .avatar-dark {
                background-color: #343a40;
                color: #fff;
            }

            .avatar-light {
                background-color: #f8f9fa;
                color: #343a40;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    new bootstrap.Tooltip(tooltipTriggerEl, {
                        delay: { show: 300, hide: 100 }
                    });
                });

                // Initialize Select2
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });

                // Get filter elements
                const searchInput = document.getElementById('searchInput');
                const categoryFilter = document.getElementById('categoryFilter');
                const statusFilter = document.getElementById('statusFilter');
                const applyFiltersBtn = document.getElementById('applyFilters');

                // Update filters function
                function updateFilters() {
                    const params = new URLSearchParams(window.location.search);

                    // Handle search
                    if (searchInput?.value.trim()) {
                        params.set('search', searchInput.value.trim());
                    } else {
                        params.delete('search');
                    }

                    // Handle category filter
                    if (categoryFilter?.value) {
                        params.set('category_filter', categoryFilter.value);
                    } else {
                        params.delete('category_filter');
                    }

                    // Handle status filter
                    if (statusFilter?.value) {
                        params.set('status_filter', statusFilter.value);
                    } else {
                        params.delete('status_filter');
                    }

                    // Update URL with new filters
                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                }

                // Add event listener for apply button
                applyFiltersBtn?.addEventListener('click', updateFilters);

                // Add event listener for Enter key in search
                searchInput?.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        updateFilters();
                    }
                });

                // Handle delete confirmation
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                const deleteForm = document.getElementById('deleteForm');
                const deleteForms = document.querySelectorAll('.delete-form');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const doctor = this.closest('tr').querySelector('.fw-medium').textContent.trim();
                        deleteForm.action = this.action;
                        document.querySelector('#deleteModal .doctor-name').textContent = doctor;
                        deleteModal.show();
                    });
                });
            });
        </script>
    @endpush

@endsection
