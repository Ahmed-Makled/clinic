@extends('layouts.admin')

@section('header_icon')
<i class="bi bi-person-badge text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
</li>
<li class="breadcrumb-item active">الأطباء</li>
@endsection

@section('actions')
    <a href="{{ route('doctors.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> إضافة
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body position-relative px-3 py-2">
        <!-- Refined Filters Section -->
        <div class="filters mb-3">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text border-0 bg-light">
                            <i class="bi bi-search opacity-75"></i>
                        </span>
                        <input type="search"
                               class="form-control form-control-sm py-2 ps-2 border-0 bg-light"
                               id="searchInput"
                               placeholder="ابحث عن طبيب...">
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select form-select-sm bg-light border-0" id="categoryFilter">
                        <option value="">كل التخصصات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Enhanced Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-nowrap bg-light">
                        <th scope="col" class="fw-medium ps-3">#</th>
                        <th scope="col" class="fw-medium">الطبيب</th>
                        <th scope="col" class="fw-medium">التخصصات</th>
                        <th scope="col" class="fw-medium">معلومات التواصل</th>
                        <th scope="col" class="fw-medium">الحالة</th>
                        <th scope="col" class="fw-medium pe-3">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($doctors as $doctor)
                        <tr>
                            <td class="ps-3">{{ $doctor->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($doctor->image)
                                        <img src="{{ Storage::url($doctor->image) }}"
                                             class="rounded-circle"
                                             width="32"
                                             height="32"
                                             style="object-fit: cover;"
                                             onerror="this.src='{{ asset('images/default-doctor.png') }}'"
                                             alt="{{ $doctor->name }}">
                                    @else
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px">
                                            <i class="bi bi-person-badge fs-6"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-medium mb-0 fs-7">{{ $doctor->name }}</div>
                                        @if($doctor->degree)
                                            <small class="text-muted fs-8">{{ $doctor->degree }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($doctor->categories as $category)
                                        <span class="badge category-badge-{{ $category->id % 6 }} fs-8"
                                              data-id="{{ $category->id }}">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="contact-info fs-7">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-envelope text-muted me-2 fs-8"></i>
                                        {{ $doctor->email }}
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-phone text-muted me-2 fs-8"></i>
                                        {{ $doctor->phone }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($doctor->status)
                                    <span class="status-badge active fs-8">
                                        <i class="bi bi-check-circle-fill"></i>
                                        نشط
                                    </span>
                                @else
                                    <span class="status-badge inactive fs-8">
                                        <i class="bi bi-x-circle-fill"></i>
                                        غير نشط
                                    </span>
                                @endif
                            </td>
                            <td class="pe-3">
                                <div class="action-buttons d-flex gap-1">
                                    <a href="{{ route('doctors.show', $doctor) }}"
                                       class="btn btn-light btn-sm px-2 py-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       data-bs-title="عرض">
                                        <i class="bi bi-eye fs-7"></i>
                                    </a>
                                    <a href="{{ route('doctors.edit', $doctor) }}"
                                       class="btn btn-light btn-sm px-2 py-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       data-bs-title="تعديل">
                                        <i class="bi bi-pencil fs-7"></i>
                                    </a>
                                    <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-light btn-sm px-2 py-1"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-title="حذف">
                                            <i class="bi bi-trash fs-7"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-people empty-state-icon opacity-50"></i>
                                    <p class="empty-state-text fs-7 mb-1">لا يوجد أطباء</p>
                                    <p class="empty-state-subtext fs-8 text-muted">قم بإضافة طبيب جديد من خلال الزر أعلاه</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $doctors->links() }}
        </div>

        <!-- Loading Overlay -->
        <div class="loading-overlay d-none">
            <div class="loading-spinner"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Refined Typography */
.fs-7 { font-size: 0.875rem; }
.fs-8 { font-size: 0.8125rem; }

/* Enhanced Table Styles */
.table > :not(caption) > * > * {
    padding: 0.75rem 0.5rem;
}

.table thead th {
    font-size: 0.8125rem;
    color: #4B5563;
    font-weight: 500;
}

/* Refined Category Badges */
.category-badge-0 { background-color: #E6F4FF; color: #0369A1; }
.category-badge-1 { background-color: #ECFDF5; color: #047857; }
.category-badge-2 { background-color: #EFF6FF; color: #1D4ED8; }
.category-badge-3 { background-color: #FEF3C7; color: #B45309; }
.category-badge-4 { background-color: #F3E8FF; color: #7E22CE; }
.category-badge-5 { background-color: #FEE2E2; color: #B91C1C; }

/* Status Badges */
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

/* Refined Action Buttons */
.action-buttons .btn {
    transition: all 0.2s ease;
    border: none;
}

.action-buttons .btn:hover {
    background-color: #F3F4F6;
}

/* Empty State */
.empty-state {
    padding: 2rem 1rem;
}

.empty-state-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

/* Loading Overlay */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.loading-spinner {
    width: 2rem;
    height: 2rem;
    border: 2px solid #E5E7EB;
    border-top-color: #3B82F6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips with a slight delay
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl, {
            delay: { show: 300, hide: 100 }
        });
    });

    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const tableRows = document.querySelectorAll('.table tbody tr');
    const loadingOverlay = document.querySelector('.loading-overlay');

    // Enhanced search with debounce
    let searchTimeout;
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const searchTerm = e.target.value.toLowerCase();

        loadingOverlay.classList.remove('d-none');

        searchTimeout = setTimeout(() => {
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });

            loadingOverlay.classList.add('d-none');
        }, 300);
    });

    // Category filter with animation
    categoryFilter.addEventListener('change', function(e) {
        const categoryId = e.target.value;
        loadingOverlay.classList.remove('d-none');

        setTimeout(() => {
            tableRows.forEach(row => {
                if (!categoryId) {
                    row.style.display = '';
                    return;
                }

                const categoryBadges = row.querySelectorAll('.badge[data-id]');
                const hasCategory = Array.from(categoryBadges).some(badge =>
                    badge.dataset.id === categoryId
                );
                row.style.display = hasCategory ? '' : 'none';
            });

            loadingOverlay.classList.add('d-none');
        }, 300);
    });

    // Delete confirmation with enhanced UX
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const doctor = this.closest('tr').querySelector('.fw-medium').textContent;

            if (confirm(`هل أنت متأكد من حذف الطبيب "${doctor}"؟`)) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                form.submit();
            }
        });
    });
});
</script>
@endpush

@endsection
