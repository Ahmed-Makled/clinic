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
            <input type="search"
                   class="form-control search-input"
                   id="searchInput"
                   placeholder="ابحث عن طبيب...">
        </div>
        <div class="control-item">
            <select class="form-select filter-select" id="categoryFilter" aria-label="اختر التخصص">
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
                    <th scope="col">#</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">التخصصات</th>
                    <th scope="col">البريد الإلكتروني</th>
                    <th scope="col">رقم الهاتف</th>
                    <th scope="col">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->id }}</td>
                        <td>{{ $doctor->name }}</td>
                        <td>
                            @foreach($doctor->categories as $category)
                                <span class="badge bg-info text-dark" data-id="{{ $category->id }}">{{ $category->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $doctor->email }}</td>
                        <td>{{ $doctor->phone }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('doctors.show', $doctor) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   data-bs-toggle="tooltip"
                                   data-bs-title="عرض التفاصيل">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('doctors.edit', $doctor) }}"
                                   class="btn btn-sm btn-outline-secondary"
                                   data-bs-toggle="tooltip"
                                   data-bs-title="تعديل">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('doctors.destroy', $doctor) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger delete-confirmation"
                                            data-bs-toggle="tooltip"
                                            data-bs-title="حذف">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

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

            const categories = row.querySelectorAll('td:nth-child(3) .badge');
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
    document.querySelectorAll('.delete-confirmation').forEach(button => {}
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
