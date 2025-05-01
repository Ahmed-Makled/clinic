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
                            <input type="search"
                                   class="form-control"
                                   id="searchInput"
                                   name="search"
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
                                <td dir="ltr">{{ $user->phone_number }}</td>
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
                                        <a href="{{ route('users.show', $user) }}" class="btn-action btn-view"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="عرض">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn-action btn-edit"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline delete-form">
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

            <div class="d-flex justify-content-between align-items-center mt-4 pagination-wrapper">
                <div class="text-muted small">
                    إجمالي النتائج: {{ $users->total() }}
                </div>
                @if($users->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($users->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($users->getUrlRange(max($users->currentPage() - 2, 1), min($users->currentPage() + 2, $users->lastPage())) as $page => $url)
                                @if ($page == $users->currentPage())
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
                            @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">
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
document.addEventListener('DOMContentLoaded', function() {
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
    searchInput?.addEventListener('keypress', function(e) {
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
        form.addEventListener('submit', function(e) {
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
    </style>
@endpush

@endsection
