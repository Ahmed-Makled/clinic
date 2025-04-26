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
                    <div class="col-md-5">
                        <label for="searchInput" class="form-label">اسم المستخدم</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search"
                                   class="form-control"
                                   id="searchInput"
                                   name="search"
                                   placeholder="ادخل اسم المستخدم..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-5">
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

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100" id="applyFilters">
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
                                <td>{{ $user->id }}</td>
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
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
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

            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Initialize Select2 for better select boxes
                $('#roleFilter').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'اختر الدور'
                });

                // Live search functionality
                const searchInput = document.getElementById('searchInput');
                const tableRows = document.querySelectorAll('.table tbody tr');

                searchInput.addEventListener('input', function (e) {
                    const searchTerm = e.target.value.toLowerCase();

                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });

                // Role filter functionality
                const roleFilter = document.getElementById('roleFilter');

                roleFilter.addEventListener('change', function (e) {
                    const roleValue = e.target.value.toLowerCase();

                    tableRows.forEach(row => {
                        if (!roleValue) {
                            row.style.display = '';
                            return;
                        }

                        const roles = row.querySelectorAll('td:nth-child(5) .badge');
                        const hasRole = Array.from(roles).some(role =>
                            role.textContent.toLowerCase().includes(roleValue)
                        );
                        row.style.display = hasRole ? '' : 'none';
                    });
                });

                // Delete confirmation using SweetAlert2
                document.querySelectorAll('.delete-confirmation').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const form = this.closest('form');

                        Swal.fire({
                            title: 'هل أنت متأكد؟',
                            text: 'سيتم حذف هذا المستخدم نهائياً',
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
                    const applyFiltersBtn = document.getElementById('applyFilters');

                    // Update filters function
                    function updateFilters() {
                        const params = new URLSearchParams(window.location.search);

                        if (searchInput?.value) params.set('search', searchInput.value);
                        if (roleFilter?.value) params.set('role_filter', roleFilter.value);

                        // Remove empty values
                        for (const [key, value] of params.entries()) {
                            if (!value) params.delete(key);
                        }

                        // Update URL with new filters
                        window.location.href = `${window.location.pathname}?${params.toString()}`;
                    }

                    // Add event listener for apply button
                    applyFiltersBtn.addEventListener('click', updateFilters);

                    // Add event listener for Enter key in search
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            updateFilters();
                        }
                    });

                    // Initialize tooltips
                    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                    tooltipTriggerList.forEach(tooltipTriggerEl => {
                        new bootstrap.Tooltip(tooltipTriggerEl);
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
            </style>
    @endpush

@endsection
