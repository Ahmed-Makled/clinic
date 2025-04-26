@extends('layouts.admin')

@section('title', 'إدارة الأطباء')

@section('actions')
    <a href="{{ route('doctors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> إضافة طبيب جديد
    </a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="card-body">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h3 class="stat-value">{{ $doctors->total() }}</h3>
                <p class="stat-label">إجمالي الأطباء</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card success">
            <div class="card-body">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h3 class="stat-value">{{ $doctors->where('status', 1)->count() }}</h3>
                <p class="stat-label">الأطباء النشطون</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card warning">
            <div class="card-body">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3 class="stat-value">{{ \App\Models\Appointment::whereDate('scheduled_at', today())->count() }}</h3>
                <p class="stat-label">مواعيد اليوم</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card info">
            <div class="card-body">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-list-check"></i>
                </div>
                <h3 class="stat-value">{{ \App\Models\Category::count() }}</h3>
                <p class="stat-label">التخصصات</p>
            </div>
        </div>
    </div>
</div>

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
                               placeholder="ابحث عن طبيب...">
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select select2" id="categoryFilter" aria-label="اختر التخصص">
                        <option value="">كل التخصصات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle data-table">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">التخصصات</th>
                        <th scope="col">البريد الإلكتروني</th>
                        <th scope="col">رقم الهاتف</th>
                        <th scope="col">الحالة</th>
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
                                        <img src="{{ Storage::url($doctor->image) }}"
                                             class="rounded-circle me-2"
                                             width="40"
                                             height="40"
                                             style="object-fit: cover;"
                                             onerror="this.onerror=null; this.src='{{ asset('images/default-doctor.png') }}';"
                                             alt="{{ $doctor->name }}">
                                    @else
                                        <div class="bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-medium">{{ $doctor->name }}</div>
                                        @if($doctor->degree)
                                            <small class="text-muted">{{ $doctor->degree }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($doctor->categories as $category)
                                    <span class="badge category-badge-{{ $category->id % 6 }} me-1"
                                          data-id="{{ $category->id }}">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope text-muted me-2"></i>
                                    {{ $doctor->email }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-phone text-muted me-2"></i>
                                    {{ $doctor->phone }}
                                </div>
                            </td>
                            <td>
                                @if($doctor->status)
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
                                    <a href="{{ route('doctors.show', $doctor) }}" class="btn-action btn-view"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="عرض">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('doctors.edit', $doctor) }}" class="btn-action btn-edit"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="d-inline delete-form">
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
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-people empty-state-icon"></i>
                                    <p class="empty-state-text">لا يوجد أطباء</p>
                                    <p class="empty-state-subtext">قم بإضافة طبيب جديد من خلال الزر أعلاه</p>
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
        width: '100%',
        placeholder: 'اختر التخصص'
    });

    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const searchInput = document.getElementById('searchInput');
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

    // Category filter functionality
    const categoryFilter = document.getElementById('categoryFilter');
    categoryFilter.addEventListener('change', function(e) {
        const categoryId = e.target.value;
        loadingOverlay.classList.remove('d-none');

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

        setTimeout(() => {
            loadingOverlay.classList.add('d-none');
        }, 300);
    });

    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('هل أنت متأكد من حذف هذا الطبيب؟')) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                form.submit();
            }
        });
    });
});
</script>

<style>
.category-badge-0 { background-color: var(--primary-bg-subtle); color: var(--primary-color); }
.category-badge-1 { background-color: var(--success-bg-subtle); color: var(--success-color); }
.category-badge-2 { background-color: var(--info-bg-subtle); color: var(--info-color); }
.category-badge-3 { background-color: var(--warning-bg-subtle); color: var(--warning-color); }
.category-badge-4 { background-color: #f3e8ff; color: #9333ea; }
.category-badge-5 { background-color: #fee2e2; color: #dc2626; }
</style>
@endpush

@endsection
