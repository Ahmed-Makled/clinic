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
                    <label for="sortFilter" class="form-label">ترتيب حسب</label>
                    <select class="form-select select2" id="sortFilter" name="sort">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>الأحدث</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>الأقدم</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>الاسم</option>
                        <option value="appointments" {{ request('sort') === 'appointments' ? 'selected' : '' }}>عدد الحجوزات</option>
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
                        <th scope="col">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px">
                                        <i class="bi bi-person text-secondary"></i>
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
                                <div class="action-buttons">
                                    <a href="{{ route('patients.show', $patient) }}" class="btn-action btn-view"
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
                            <td colspan="8" class="text-center py-4">
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
    const sortFilter = document.getElementById('sortFilter');
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

        if (sortFilter?.value?.trim()) {
            params.set('sort', sortFilter.value.trim());
        } else {
            params.delete('sort');
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
