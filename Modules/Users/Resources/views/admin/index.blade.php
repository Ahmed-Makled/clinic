@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('header_icon')
    <i class="bi bi-people text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item active">المستخدمين</li>
@endsection

@section('actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> إضافة
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="filters mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="searchInput" class="form-label">بحث بالاسم/البريد/الهاتف</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" class="form-control" id="searchInput" name="search"
                                placeholder="البحث بالاسم، البريد الإلكتروني، أو رقم الهاتف..."
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="roleFilter" class="form-label">الدور</label>
                        <select class="form-select select2" id="roleFilter" name="role_filter">
                            <option value="">الكل</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role_filter') === $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
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

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">الاسم</th>
                            <th scope="col">البريد الإلكتروني</th>
                            <th scope="col">رقم الهاتف</th>
                            <th scope="col">الدور</th>
                            <th scope="col">الحالة</th>
                            <th scope="col">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td dir="rtl">{{ $user->phone_number }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="role-badge {{ strtolower($role->name) }}-role">
                                            <i class="bi bi-shield-check me-1"></i>
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->status)
                                        <span class="status-badge active">
                                            <i class="bi bi-check-circle-fill"></i>
                                            نشط
                                        </span>
                                    @else
                                        <span class="status-badge inactive">
                                            <i class="bi bi-x-circle-fill"></i>
                                            غير نشط
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('users.details', $user) }}" class="btn-action btn-view"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="عرض">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn-action btn-edit"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete delete-confirmation"
                                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-people display-6 d-block mb-3 opacity-50"></i>
                                        <p class="h5 opacity-75">لا يوجد مستخدمين</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="pagination-wrapper mt-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="pagination-info">
                                <span class="text-muted">
                                    عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من أصل {{ $users->total() }} نتيجة
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                <ul class="pagination custom-pagination mb-0">
                                    {{-- First Page Link --}}
                                    @if (!$users->onFirstPage())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url(1) }}" title="الصفحة الأولى">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Previous Page Link --}}
                                    @if ($users->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->previousPageUrl() }}" title="الصفحة السابقة">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $start = max($users->currentPage() - 2, 1);
                                        $end = min($users->currentPage() + 2, $users->lastPage());

                                        $showStartDots = $start > 2;
                                        $showEndDots = $end < $users->lastPage() - 1;
                                    @endphp

                                    {{-- Always show first page if not in range --}}
                                    @if ($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url(1) }}">1</a>
                                        </li>
                                        @if ($showStartDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $users->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $users->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Always show last page if not in range --}}
                                    @if ($end < $users->lastPage())
                                        @if ($showEndDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url($users->lastPage()) }}">{{ $users->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($users->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->nextPageUrl() }}" title="الصفحة التالية">
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
                                    @if ($users->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url($users->lastPage()) }}" title="الصفحة الأخيرة">
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
                        <span class="text-muted">إجمالي النتائج: {{ $users->total() }}</span>
                    </div>
                </div>
            @endif
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
                    <p>هل أنت متأكد من حذف المستخدم "<span class="user-name fw-bold"></span>"؟</p>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Select2
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });

                // Get filter elements
                const searchInput = document.getElementById('searchInput');
                const roleFilter = document.getElementById('roleFilter');
                const statusFilter = document.getElementById('statusFilter');
                const applyFiltersBtn = document.getElementById('applyFilters');

                // Update filters function
                function updateFilters() {
                    const params = new URLSearchParams(window.location.search);

                    if (searchInput?.value?.trim()) {
                        params.set('search', searchInput.value.trim());
                    } else {
                        params.delete('search');
                    }

                    if (roleFilter?.value?.trim()) {
                        params.set('role_filter', roleFilter.value.trim());
                    } else {
                        params.delete('role_filter');
                    }

                    if (statusFilter?.value?.trim()) {
                        params.set('status_filter', statusFilter.value.trim());
                    } else {
                        params.delete('status_filter');
                    }

                    // تحديث الرابط مع الفلاتر
                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                }

                // Add event listeners
                applyFiltersBtn?.addEventListener('click', updateFilters);
                searchInput?.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') updateFilters();
                });

                // Initialize tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Handle delete confirmation
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                const deleteForm = document.getElementById('deleteForm');
                const deleteForms = document.querySelectorAll('.delete-form');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const userName = this.closest('tr').querySelector('.fw-medium').textContent.trim();
                        deleteForm.action = this.action;
                        document.querySelector('#deleteModal .user-name').textContent = userName;
                        deleteModal.show();
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
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
        </style>
    @endpush

@endsection
