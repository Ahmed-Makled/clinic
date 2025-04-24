@extends('admin::layouts.master')

@section('title', 'إدارة المرضى')

@section('actions')
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة مريض جديد
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           id="searchInput"
                           placeholder="ابحث عن مريض...">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="genderFilter">
                    <option value="">كل الأنواع</option>
                    <option value="male">ذكر</option>
                    <option value="female">أنثى</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="patientsTable">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>العنوان</th>
                        <th>الجنس</th>
                        <th>تاريخ الميلاد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->phone }}</td>
                        <td>{{ $patient->address ?: 'غير محدد' }}</td>
                        <td>{{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                        <td>{{ $patient->date_of_birth ? date('Y-m-d', strtotime($patient->date_of_birth)) : 'غير محدد' }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.patients.edit', $patient) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.patients.destroy', $patient) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger delete-confirmation">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noResultsRow" class="d-none">
                        <td colspan="7" class="text-center">لا توجد نتائج مطابقة للبحث</td>
                    </tr>
                    <tr id="noDataRow"></tr>
                        <td colspan="7" class="text-center">لا يوجد مرضى حالياً</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {}
    const searchInput = $('#searchInput');
    const genderFilter = $('#genderFilter');
    const patientsTable = $('#patientsTable tbody');
    const noResultsRow = $('#noResultsRow');
    const noDataRow = $('#noDataRow');

    function filterTable() {
        const searchText = searchInput.val().toLowerCase();
        const selectedGender = genderFilter.val();
        let hasResults = false;

        patientsTable.find('tr').not('#noResultsRow, #noDataRow').each(function() {
            const row = $(this);
            const name = row.find('td:eq(0)').text().toLowerCase();
            const email = row.find('td:eq(1)').text().toLowerCase();
            const phone = row.find('td:eq(2)').text().toLowerCase();
            const address = row.find('td:eq(3)').text().toLowerCase();
            const gender = row.find('td:eq(4)').text() === 'ذكر' ? 'male' : 'female';

            const matchesSearch = name.includes(searchText) ||
                                email.includes(searchText) ||
                                phone.includes(searchText) ||
                                address.includes(searchText);

            const matchesGender = !selectedGender || gender === selectedGender;

            if (matchesSearch && matchesGender) {
                row.removeClass('d-none');
                hasResults = true;
            } else {
                row.addClass('d-none');
            }
        });

        noResultsRow.toggleClass('d-none', hasResults);
        noDataRow.addClass('d-none');
    }

    searchInput.on('keyup', filterTable);
    genderFilter.on('change', filterTable);
});
</script>
@endpush
