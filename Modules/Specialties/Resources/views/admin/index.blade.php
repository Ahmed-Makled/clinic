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
        <div class="filters mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="searchInput" class="form-label">اسم التخصص</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search"
                               class="form-control"
                               id="searchInput"
                               name="search"
                               placeholder="ادخل اسم التخصص..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="statusFilter" class="form-label">الحالة</label>
                    <select class="form-select" id="statusFilter" name="status">
                        <option value="">الكل</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غير نشط</option>
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
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-dark-subtle rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width: 32px; height: 32px">
                                        <i class="bi bi-heart-pulse text-dark"></i>
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
                                    <form action="{{ route('specialties.destroy', $specialty) }}" method="POST" class="d-inline delete-form">
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

        <!-- Enhanced Pagination -->
        @if($specialties->hasPages())
            <div class="pagination-wrapper">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="pagination-info">
                            عرض {{ $specialties->firstItem() }} إلى {{ $specialties->lastItem() }} من {{ $specialties->total() }} نتيجة
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav class="pagination-nav">
                            <ul class="pagination justify-content-center justify-content-md-end">
                                {{-- First Page Link --}}
                                @if ($specialties->currentPage() > 3)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $specialties->url(1) }}" aria-label="الصفحة الأولى">
                                            <i class="bi bi-chevron-double-right"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Previous Page Link --}}
                                @if ($specialties->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $specialties->previousPageUrl() }}" rel="prev" aria-label="السابق">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @php
                                    $start = max($specialties->currentPage() - 2, 1);
                                    $end = min($start + 4, $specialties->lastPage());
                                    $start = max($end - 4, 1);
                                @endphp

                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $specialties->url(1) }}">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    @if ($i == $specialties->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $specialties->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                @if($end < $specialties->lastPage())
                                    @if($end < $specialties->lastPage() - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $specialties->url($specialties->lastPage()) }}">{{ $specialties->lastPage() }}</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($specialties->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $specialties->nextPageUrl() }}" rel="next" aria-label="التالي">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                    </li>
                                @endif

                                {{-- Last Page Link --}}
                                @if ($specialties->currentPage() < $specialties->lastPage() - 2)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $specialties->url($specialties->lastPage()) }}" aria-label="الصفحة الأخيرة">
                                            <i class="bi bi-chevron-double-left"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        @endif
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
                <p>هل أنت متأكد من حذف تخصص "<span class="specialty-name fw-bold"></span>"؟</p>
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

    /* Enhanced Pagination Styles */
    .pagination-wrapper {
        padding-top: 1.5rem;
        border-top: 1px solid var(--bs-gray-200);
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 0 0 0.5rem 0.5rem;
        padding: 1.5rem;
        margin: 1.5rem -1.5rem -1.5rem -1.5rem;
    }

    .pagination-info {
        color: var(--bs-gray-700);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .pagination-nav .pagination {
        margin-bottom: 0;
        gap: 0.25rem;
    }

    .pagination .page-link {
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

    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        min-width: 2.5rem;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--bs-primary) 0%, #0056b3 100%);
        border-color: var(--bs-primary);
        color: white;
        box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.3);
        transform: translateY(-1px);
    }

    .pagination .page-link:hover:not(.disabled) {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.2);
    }

    .pagination .page-item.disabled .page-link {
        background-color: var(--bs-gray-100);
        border-color: var(--bs-gray-200);
        color: var(--bs-gray-400);
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .pagination .page-link.dots {
        border: none;
        background: transparent;
        box-shadow: none;
        color: var(--bs-gray-500);
        cursor: default;
        font-weight: 600;
        letter-spacing: 0.1em;
    }

    .pagination .page-item.disabled .page-link.dots {
        background: transparent;
        border: none;
    }

    .pagination .page-link i {
        font-size: 0.75rem;
        line-height: 1;
    }

    /* Responsive pagination */
    @media (max-width: 768px) {
        .pagination-wrapper .row {
            flex-direction: column-reverse;
            gap: 1rem;
        }

        .pagination-wrapper .col-md-6 {
            text-align: center;
        }

        .pagination-nav .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination .page-link {
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
        .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
            display: none;
        }
    }

    /* Status badges styling */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
        font-weight: 500;
    }

    .status-badge.active {
        background-color: #ECFDF5;
        color: #047857;
    }

    .status-badge.inactive {
        background-color: #FEF2F2;
        color: #B91C1C;
    }

    .status-badge i {
        font-size: 0.75rem;
    }

    /* Action buttons styling */
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
        border: none;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .btn-edit {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .btn-edit:hover {
        background-color: #0d6efd;
        color: white;
        transform: translateY(-1px);
    }

    .btn-delete {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .btn-delete:hover {
        background-color: #dc3545;
        color: white;
        transform: translateY(-1px);
    }
</style>
@endpush

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
    const statusFilter = document.getElementById('statusFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');

    // Update filters function
    function updateFilters() {
        const params = new URLSearchParams(window.location.search);

        if (searchInput?.value) params.set('search', searchInput.value);
        if (statusFilter?.value) params.set('status', statusFilter.value);

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

    // Live search functionality
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

    // Handle delete confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const specialty = this.closest('tr').querySelector('.d-flex.align-items-center div:last-child').textContent.trim();
            deleteForm.action = this.action;
            document.querySelector('#deleteModal .specialty-name').textContent = specialty;
            deleteModal.show();
        });
    });
});
</script>
@endpush

@endsection
