@extends('layouts.admin')

@section('title', 'إدارة الأطباء')

@section('actions')
    <a href="{{ route('doctors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> إضافة طبيب جديد
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
                        <input type="search"
                               class="form-control"
                               id="searchInput"
                               placeholder="ابحث عن طبيب...">
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="categoryFilter" aria-label="اختر التخصص">
                        <option value="">كل التخصصات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($doctor->image)
                                        <img src="{{ asset($doctor->image) }}"
                                             class="rounded-circle me-2"
                                             width="32"
                                             height="32"
                                             alt="{{ $doctor->name }}">
                                    @else
                                        <div class="bg-light rounded-circle me-2 d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                    @endif
                                    <div>{{ $doctor->name }}</div>
                                </div>
                            </td>
                            <td>
                                @foreach($doctor->categories as $category)
                                    <span class="badge bg-info bg-opacity-10 text-info"
                                          data-id="{{ $category->id }}">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>{{ $doctor->email }}</td>
                            <td>{{ $doctor->phone }}</td>
                            <td>
                                <div class="btn-group">
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
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-person-badge display-6 d-block mb-3"></i>
                                    <p class="h5">لا يوجد أطباء</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $doctors->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Select2 for better select boxes
    $('#categoryFilter').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'اختر التخصص'
    });

    // Live search functionality
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('.table tbody tr');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Category filter functionality
    const categoryFilter = document.getElementById('categoryFilter');

    categoryFilter.addEventListener('change', function(e) {
        const categoryId = e.target.value;

        tableRows.forEach(row => {
            if (!categoryId) {
                row.style.display = '';
                return;
            }

            const categories = row.querySelectorAll('td:nth-child(3) .badge');
            const hasCategory = Array.from(categories).some(category =>
                category.dataset.id === categoryId
            );
            row.style.display = hasCategory ? '' : 'none';
        });
    });

    // Delete confirmation using SweetAlert2
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف هذا الطبيب نهائياً',
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


@endsection
