@extends('layouts.admin')

@section('title', 'إدارة المرضى')

@section('actions')
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> إضافة مريض جديد
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search"
                               class="form-control"
                               id="searchInput"
                               placeholder="ابحث عن مريض...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="genderFilter" aria-label="اختر النوع">
                        <option value="">كل الأنواع</option>
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
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
                                         style="width: 32px; height: 32px">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                    <div>{{ $patient->name }}</div>
                                </div>
                            </td>
                            <td>{{ $patient->email }}</td>
                            <td>{{ $patient->phone }}</td>
                            <td>
                                @if($patient->gender == 'male')
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-gender-male me-1"></i>ذكر
                                    </span>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-gender-female me-1"></i>أنثى
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($patient->date_of_birth)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 me-2 text-muted"></i>
                                        {{ $patient->date_of_birth->format('Y-m-d') }}
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    {{ $patient->appointments_count ?? 0 }} موعد
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('patients.show', $patient) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       data-bs-title="عرض التفاصيل">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('patients.edit', $patient) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       data-bs-toggle="tooltip"
                                       data-bs-title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('patients.destroy', $patient) }}"
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
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-people display-6 d-block mb-3"></i>
                                    <p class="h5">لا يوجد مرضى</p>
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
    $('#genderFilter').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'اختر النوع'
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

    // Gender filter functionality
    const genderFilter = document.getElementById('genderFilter');

    genderFilter.addEventListener('change', function(e) {
        const gender = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            if (!gender) {
                row.style.display = '';
                return;
            }

            const genderCell = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            row.style.display = genderCell.includes(gender) ? '' : 'none';
        });
    });

    // Delete confirmation using SweetAlert2
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف هذا المريض نهائياً',
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

@push('styles')
<style>
    .badge {
        font-weight: 500;
    }

    .table th {
        font-weight: 600;
    }

    .btn-group > .btn {
        padding: 0.375rem 0.5rem;
    }

    .table > :not(:first-child) {
        border-top: none;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(3.5rem + 2px);
        padding: 1rem 0.75rem;
    }
</style>
@endpush

@endsection
