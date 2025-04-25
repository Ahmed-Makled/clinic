@extends('layouts.admin')

@section('title', 'إدارة المرضى')

@section('actions')
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة مريض جديد
    </a>
@endsection

@section('content')
<div class="modern-table-container">
    <div class="table-controls">
        <div class="control-item">
            <input type="text"
                   class="search-input"
                   id="searchInput"
                   placeholder="ابحث عن مريض...">
        </div>
        <div class="control-item">
            <select class="filter-select" id="genderFilter">
                <option value="">كل الأنواع</option>
                <option value="male">ذكر</option>
                <option value="female">أنثى</option>
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
                    <th>النوع</th>
                    <th>تاريخ الميلاد</th>
                    <th>عدد المواعيد</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td>{{ $patient->id }}</td>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->phone }}</td>
                        <td>
                            <span class="status-badge {{ $patient->gender == 'male' ? 'status-badge-active' : 'status-badge-pending' }}">
                                {{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}
                            </span>
                        </td>
                        <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '-' }}</td>
                        <td>
                            <span class="status-badge">
                                {{ $patient->appointments_count ?? 0 }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('patients.show', $patient) }}"
                                   class="btn-action btn-view">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}"
                                   class="btn-action btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('patients.destroy', $patient) }}"
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
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-people empty-state-icon"></i>
                                <p class="empty-state-text">لا يوجد مرضى</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $patients->links() }}
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

    // تصفية حسب النوع
    const genderFilter = document.getElementById('genderFilter');

    genderFilter.addEventListener('change', function(e) {
        const gender = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            if (!gender) {
                row.style.display = '';
                return;
            }

            const genderCell = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            row.style.display = genderCell.includes(gender) ? '' : 'none';
        });
    });

    // تأكيد الحذف
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا المريض؟')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush

@endsection
