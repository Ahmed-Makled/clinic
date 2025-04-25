@extends('layouts.admin')

@section('title', 'إدارة الأطباء')

@section('actions')
    <a href="{{ route('doctors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة طبيب جديد
    </a>
@endsection

@section('content')
<div class="modern-table-container">
    <div class="table-controls">
        <div class="control-item">
            <input type="text"
                   class="search-input"
                   id="searchInput"
                   placeholder="ابحث عن طبيب...">
        </div>
        <div class="control-item">
            <select class="filter-select" id="categoryFilter">
                <option value="">كل التخصصات</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>التخصصات</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->id }}</td>
                        <td>{{ $doctor->name }}</td>
                        <td>
                            @foreach($doctor->categories as $category)
                                <span class="status-badge status-badge-pending" data-id="{{ $category->id }}">{{ $category->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $doctor->email }}</td>
                        <td>{{ $doctor->phone }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('doctors.show', $doctor) }}"
                                   class="btn-action btn-view">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('doctors.edit', $doctor) }}"
                                   class="btn-action btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('doctors.destroy', $doctor) }}"
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
                                <i class="bi bi-person-badge empty-state-icon"></i>
                                <p class="empty-state-text">لا يوجد أطباء</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $doctors->links() }}
    </div>
</div>

@push('styles')
<style>
    .modern-table-container {
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        padding: 1.5rem;
    }

    .table-controls {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .control-item {
        flex: 1;
    }

    .search-input,
    .filter-select {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .modern-table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        border-collapse: collapse;
    }

    .modern-table th,
    .modern-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }

    .modern-table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-align: right;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: 0.25rem;
        background-color: #e9ecef;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-start;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0.375rem;
        border: none;
        background: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-view {
        color: #0d6efd;
    }

    .btn-edit {
        color: #198754;
    }

    .btn-delete {
        color: #dc3545;
    }

    .btn-action:hover {
        background-color: #f8f9fa;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: #6c757d;
        margin-bottom: 0;
    }

    .pagination-container {
        margin-top: 1.5rem;
    }
</style>
@endpush

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

    // تصفية حسب التخصص
    const categoryFilter = document.getElementById('categoryFilter');

    categoryFilter.addEventListener('change', function(e) {
        const categoryId = e.target.value;

        tableRows.forEach(row => {
            if (!categoryId) {
                row.style.display = '';
                return;
            }

            const categories = row.querySelectorAll('td:nth-child(3) .status-badge');
            let hasCategory = false;
            categories.forEach(category => {
                if (category.dataset.id === categoryId) {
                    hasCategory = true;
                }
            });
            row.style.display = hasCategory ? '' : 'none';
        });
    });

    // تأكيد الحذف
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا الطبيب؟')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush

@endsection