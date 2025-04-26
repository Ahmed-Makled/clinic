@extends('layouts.admin')

@section('title', 'التخصصات')

@section('header_icon')
<i class="bi bi-heart-pulse text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
</li>
<li class="breadcrumb-item active">التخصصات</li>
@endsection

@section('actions')
    <a href="{{ route('specialties.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> إضافة
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="search"
                       class="form-control"
                       id="searchInput"
                       placeholder="ابحث عن تخصص...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">اسم التخصص</th>
                        <th scope="col">عدد الأطباء</th>
                        <th scope="col">الوصف</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specialties as $specialty)
                        <tr>
                            <td>{{ $specialty->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width: 32px; height: 32px">
                                        <i class="bi bi-heart-pulse text-info"></i>
                                    </div>
                                    <div>{{ $specialty->name }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $specialty->doctors_count ?? 0 }} طبيب
                                </span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 300px;">
                                    {{ $specialty->description }}
                                </span>
                            </td>
                            <td>

                                @if($specialty->status)
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
                                    <a href="{{ route('specialties.edit', $specialty) }}" class="btn-action btn-edit"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('specialties.destroy', $specialty) }}" method="POST" class="d-inline">
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
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-list-check display-6 d-block mb-3"></i>
                                    <p class="h5">لا توجد تخصصات</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $specialties->links() }}
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

    // Live search functionality
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('.table tbody tr');

    searchInput.addEventListener('input', function() {
        const searchQuery = this.value.toLowerCase();

        tableRows.forEach(row => {
            const specialtyName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const description = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

            row.style.display = specialtyName.includes(searchQuery) ||
                              description.includes(searchQuery) ? '' : 'none';
        });
    });

    // Delete confirmation using SweetAlert2
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف هذا التخصص نهائياً',
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
