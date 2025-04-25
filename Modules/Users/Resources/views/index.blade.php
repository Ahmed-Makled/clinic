@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> إضافة مستخدم جديد
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="search" class="form-control" id="searchInput" placeholder="ابحث عن مستخدم...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="roleFilter" aria-label="اختر الدور">
                            <option value="">كل الأدوار</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
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
    @endpush

    @push('styles')
        <style>
            .hover-primary:hover {
                color: var(--bs-primary) !important;
                transform: scale(1.1);
                transition: all 0.2s ease-in-out;
            }

            .hover-danger:hover {
                color: var(--bs-danger) !important;
                transform: scale(1.1);
                transition: all 0.2s ease-in-out;
            }

            .btn:focus {
                box-shadow: none;
            }

            /* Avatar Circle */
            .avatar-circle {
                width: 35px;
                height: 35px;
                background-color: var(--bs-primary-bg-subtle);
                color: var(--bs-primary);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Role Badges */
            .badge {
                background-color: var(--bs-primary-bg-subtle);
                color: var(--bs-primary);
                font-weight: 500;
                font-size: 0.75rem;
            }

            /* Status Badges */
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.35rem 0.75rem;
                border-radius: 1rem;
                font-size: 0.75rem;
                font-weight: 500;
            }

            .status-badge.active {
                background-color: #DFF6DD;
                color: #2E7D32;
            }

            .status-badge.inactive {
                background-color: #FFEBEE;
                color: #C62828;
            }


            /* Action Buttons */
            .action-btn {
                padding: 0.4rem;
                font-size: 1.1rem;
                color: var(--bs-gray-600);
                border-radius: 0.375rem;
                border: none;
                background: transparent;
                line-height: 1;
                transition: all 0.2s ease-in-out;
            }

            .action-btn:hover {
                background-color: var(--bs-light);
                color: var(--bs-primary);
            }

            .delete-btn:hover {
                background-color: var(--bs-danger-bg-subtle);
                color: var(--bs-danger);
            }

            /* Table Improvements */
            .table> :not(caption)>*>* {
                padding: 1rem 0.75rem;
            }

            .table tbody tr:hover {
                background-color: var(--bs-tertiary-bg);
            }

            .action-buttons {
                display: flex;
                gap: 0.5rem;
                align-items: center;
            }

            .btn-action {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                padding: 0;
                border: none;
                background: transparent;
                border-radius: 6px;
                color: var(--bs-gray-600);
                transition: all 0.2s ease-in-out;
                cursor: pointer;
            }

            .btn-action i {
                font-size: 1.1rem;
                line-height: 1;
            }

            .btn-action:focus {
                outline: none;
                box-shadow: none;
            }


            .btn-delete:hover {
                color: #e53935;

            }

            .btn-edit:hover {
                color: #1e88e5;
            }

            .btn-view:hover {
                color: #43a047;
                background-color: var(--bs-success-bg-subtle);

            }

            /* Role Badges */
            .role-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.4rem 0.75rem;
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 500;
                margin: 0.1rem;
                transition: all 0.2s ease-in-out;
            }

            .admin-role {
                background-color: #E3F2FD;
                color: #1565C0;
            }

            .doctor-role {
                background-color: #E8F5E9;
                color: #2E7D32;
            }

            .patient-role {
                background-color: #E0F7FA;
                color: #00838F;
            }

            .receptionist-role {
                background-color: #FFF3E0;
                color: #EF6C00;
            }

            .staff-role {
                background-color: #ECEFF1;
                color: #455A64;
            }



            .role-badge i {
                font-size: 0.8rem;
                opacity: 0.8;
            }
        </style>
    @endpush

@endsection
