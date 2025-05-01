@extends('layouts.admin')

@section('title', 'إدارة المرضى')

@section('header_icon')
<i class="bi bi-person-vcard text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
</li>
<li class="breadcrumb-item active">المرضى</li>
@endsection

@section('actions')
    <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> إضافة
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body position-relative">
        <div class="filters mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="searchInput" class="form-label">اسم المريض</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search"
                               class="form-control"
                               id="searchInput"
                               name="search"
                               placeholder="ادخل اسم المريض..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="genderFilter" class="form-label">النوع</label>
                    <select class="form-select select2" id="genderFilter" name="gender_filter">
                        <option value="">الكل</option>
                        <option value="male" {{ request('gender_filter') === 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ request('gender_filter') === 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">الحالة</label>
                    <select class="form-select select2" id="statusFilter" name="status_filter">
                        <option value="">الكل</option>
                        <option value="1" {{ request('status_filter') === '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('status_filter') === '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-primary d-flex align-items-center" id="applyFilters">
                        <i class="bi bi-funnel-fill me-1"></i>
                        تطبيق
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">البريد الإلكتروني</th>
                        <th scope="col">رقم الهاتف</th>
                        <th scope="col">النوع</th>
                        <th scope="col">تاريخ الميلاد</th>
                        <th scope="col">عدد الحجوزات</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-dark-subtle text-dark rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px">
                                        <i class="bi bi-person "></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $patient->name }}</div>
                                        @if($patient->patient?->blood_type)
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="bi bi-droplet-fill me-1"></i>{{ $patient->patient->blood_type }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope text-muted me-2"></i>
                                    {{ $patient->email }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-phone text-muted me-2"></i>
                                    {{ $patient->phone_number }}
                                </div>
                            </td>
                            <td>
                                @if($patient->patient?->gender == 'male')
                                    <span class=" text-primary">
                                        <i class="bi bi-gender-male me-1"></i>ذكر
                                    </span>
                                @else
                                    <span class="" style="color: #db4488">
                                        <i class="bi bi-gender-female me-1"></i>أنثى
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($patient->patient?->date_of_birth)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 me-2 text-muted"></i>
                                        {{ $patient->patient->date_of_birth->format('Y-m-d') }}
                                        <small class="text-muted ms-2">({{ $patient->patient->age }} سنة)</small>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-dark">
                                    {{ $patient->appointments_count ?? 0 }} موعد
                                </span>
                            </td>
                            <td>
                                @if($patient->status)
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
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('patients.details', $patient) }}" class="btn-action btn-view"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="عرض">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('patients.edit', $patient) }}" class="btn-action btn-edit"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-people empty-state-icon"></i>
                                    <p class="empty-state-text">لا يوجد مرضى</p>
                                    <p class="empty-state-subtext">قم بإضافة مريض جديد من خلال الزر أعلاه</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pagination-wrapper">
            <div class="text-muted small">
                إجمالي النتائج: {{ $patients->total() }}
            </div>
            @if($patients->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if ($patients->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $patients->previousPageUrl() }}" rel="prev">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($patients->getUrlRange(max($patients->currentPage() - 2, 1), min($patients->currentPage() + 2, $patients->lastPage())) as $page => $url)
                            @if ($page == $patients->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($patients->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $patients->nextPageUrl() }}" rel="next">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">
                                    <i class="bi bi-chevron-left"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $patients->links() }}
        </div>

        <!-- Loading Overlay -->
        <div class="loading-overlay d-none">
            <div class="loading-spinner"></div>
        </div>
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
                <p>هل أنت متأكد من حذف المريض "<span class="patient-name fw-bold"></span>"؟</p>
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

    .pagination-wrapper {
        padding-top: 1rem;
        border-top: 1px solid var(--bs-gray-200);
    }

    .pagination {
        margin: 0;
    }

    .pagination .page-item {
        margin: 0 2px;
    }

    .pagination .page-link {
        border-radius: 4px;
        border: 1px solid var(--bs-gray-300);
        color: var(--bs-gray-700);
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease-in-out;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
        box-shadow: 0 2px 4px rgba(var(--bs-primary-rgb), 0.2);
    }

    .pagination .page-link:hover:not(.disabled) {
        background-color: var(--bs-gray-100);
        border-color: var(--bs-gray-400);
        color: var(--bs-primary);
    }

    .pagination .page-item.disabled .page-link {
        background-color: var(--bs-gray-100);
        border-color: var(--bs-gray-200);
        color: var(--bs-gray-400);
    }

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
    const genderFilter = document.getElementById('genderFilter');
    const statusFilter = document.getElementById('statusFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');

    // Update filters function
    function updateFilters() {
        const params = new URLSearchParams(window.location.search);

        if (searchInput?.value?.trim()) {
            params.set('search', searchInput.value.trim());
        } else {
            params.delete('search');
        }

        if (genderFilter?.value?.trim()) {
            params.set('gender_filter', genderFilter.value.trim());
        } else {
            params.delete('gender_filter');
        }

        if (statusFilter?.value?.trim()) {
            params.set('status_filter', statusFilter.value.trim());
        } else {
            params.delete('status_filter');
        }

        // Update URL with new filters
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Add event listeners
    applyFiltersBtn?.addEventListener('click', updateFilters);

    // Handle Enter key in search
    searchInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') updateFilters();
    });

    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle delete confirmation
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const patientName = this.closest('tr').querySelector('.fw-medium').textContent.trim();
            deleteForm.action = this.action;
            document.querySelector('#deleteModal .patient-name').textContent = patientName;
            deleteModal.show();
        });
    });
});
</script>
@endpush

@endsection
