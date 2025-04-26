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
        <div class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search"
                               class="form-control"
                               id="searchInput"
                               placeholder="ابحث عن مريض...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select select2" id="genderFilter">
                        <option value="">كل الأنواع</option>
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select select2" id="sortFilter">
                        <option value="latest">الأحدث</option>
                        <option value="oldest">الأقدم</option>
                        <option value="name">الاسم</option>
                        <option value="appointments">عدد المواعيد</option>
                    </select>
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
                        <th scope="col">عدد المواعيد</th>
                        <th scope="col">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>{{ $patient->id }}</td>
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
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-gender-male me-1"></i>ذكر
                                    </span>
                                @else
                                    <span class="badge bg-pink bg-opacity-10" style="color: #db4488">
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
                                <span class="badge bg-info bg-opacity-10 text-info">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const searchInput = document.getElementById('searchInput');
    const genderFilter = document.getElementById('genderFilter');
    const sortFilter = document.getElementById('sortFilter');
    const tableRows = document.querySelectorAll('.table tbody tr');
    const loadingOverlay = document.querySelector('.loading-overlay');

    // Live search functionality with debounce
    let searchTimeout;
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const searchTerm = e.target.value.toLowerCase();

        searchTimeout = setTimeout(() => {
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }, 300);
    });

    // Gender filter functionality
    genderFilter.addEventListener('change', function(e) {
        const gender = e.target.value;
        loadingOverlay.classList.remove('d-none');

        tableRows.forEach(row => {
            if (!gender) {
                row.style.display = '';
                return;
            }

            const genderBadge = row.querySelector('.badge i');
            const isMatch = gender === 'male' ?
                genderBadge?.classList.contains('bi-gender-male') :
                genderBadge?.classList.contains('bi-gender-female');

            row.style.display = isMatch ? '' : 'none';
        });

        setTimeout(() => {
            loadingOverlay.classList.add('d-none');
        }, 300);
    });

    // Sort functionality
    sortFilter.addEventListener('change', function(e) {
        const sortBy = e.target.value;
        const tbody = document.querySelector('.table tbody');
        const rows = Array.from(tableRows);
        loadingOverlay.classList.remove('d-none');

        rows.sort((a, b) => {
            switch(sortBy) {
                case 'name':
                    const nameA = a.querySelector('.fw-medium').textContent;
                    const nameB = b.querySelector('.fw-medium').textContent;
                    return nameA.localeCompare(nameB);
                case 'appointments':
                    const apptsA = parseInt(a.querySelector('.badge.bg-info')?.textContent);
                    const apptsB = parseInt(b.querySelector('.badge.bg-info')?.textContent);
                    return apptsB - apptsA;
                case 'oldest':
                    return a.querySelector('td:first-child').textContent -
                           b.querySelector('td:first-child').textContent;
                default: // latest
                    return b.querySelector('td:first-child').textContent -
                           a.querySelector('td:first-child').textContent;
            }
        });

        rows.forEach(row => tbody.appendChild(row));

        setTimeout(() => {
            loadingOverlay.classList.add('d-none');
        }, 300);
    });

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('هل أنت متأكد من حذف هذا المريض؟')) {
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
