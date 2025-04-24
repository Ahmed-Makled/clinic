@extends('admin::layouts.master')

@section('title', 'إدارة المستخدمين')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> مستخدم جديد
    </a>
@endsection

@section('content')
<div class="modern-table-container">
    <div class="table-controls">
        <div class="control-item">
            <input type="text"
                   class="search-input"
                   id="searchInput"
                   placeholder="بحث عن مستخدم...">
        </div>
        <div class="control-item">
            <select class="filter-select" id="roleFilter">
                <option value="">كل الأدوار</option>
                <option value="admin">مدير</option>
                <option value="doctor">طبيب</option>
                <option value="patient">مريض</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>الأدوار</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="status-badge {{ $role->name == 'Administrator' ? 'status-badge-active' : 'status-badge-pending' }}">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn-action btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action btn-delete delete-confirmation">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-people empty-state-icon"></i>
                                <p class="empty-state-text">لا يوجد مستخدمين</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $users->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // البحث المباشر
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('.modern-table tbody tr');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // تصفية حسب الدور
    const roleFilter = document.getElementById('roleFilter');

    roleFilter.addEventListener('change', function(e) {
        const role = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            if (!role) {
                row.style.display = '';
                return;
            }

            const roles = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            row.style.display = roles.includes(role) ? '' : 'none';
        });
    });

    // تأكيد الحذف
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush

@endsection
