@extends('admin::layouts.master')

@section('title', 'إدارة الأطباء')

@section('actions')
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة طبيب جديد
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
                           placeholder="ابحث عن طبيب...">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="categoryFilter">
                    <option value="">كل التخصصات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="doctorsTable">
                <thead>
                    <tr>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>التخصصات</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                    <tr>
                        <td></td>
                            <img src="{{ $doctor->image ? asset('storage/' . $doctor->image) : asset('images/default-doctor.png') }}"
                                 alt="{{ $doctor->name }}"
                                 class="rounded-circle"
                                 width="40">
                        </td>
                        <td>{{ $doctor->name }}</td>
                        <td>
                            @foreach($doctor->categories as $category)
                                <span class="badge bg-info">{{ $category->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $doctor->email }}</td>
                        <td>{{ $doctor->phone }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.doctors.edit', $doctor) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.doctors.destroy', $doctor) }}"
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
                    <tr id="noResultsRow" class="d-none"></tr>
                        <td colspan="6" class="text-center">لا توجد نتائج مطابقة للبحث</td>
                    </tr>
                    <tr id="noDataRow"></tr>
                        <td colspan="6" class="text-center">لا يوجد أطباء حالياً</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4"></div>
            {{ $doctors->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const searchInput = $('#searchInput');
    const categoryFilter = $('#categoryFilter');
    const doctorsTable = $('#doctorsTable tbody');
    const noResultsRow = $('#noResultsRow');
    const noDataRow = $('#noDataRow');

    function filterTable() {
        const searchText = searchInput.val().toLowerCase();
        const selectedCategory = categoryFilter.val();
        let hasResults = false;

        doctorsTable.find('tr').not('#noResultsRow, #noDataRow').each(function() {
            const row = $(this);
            const name = row.find('td:eq(1)').text().toLowerCase();
            const email = row.find('td:eq(3)').text().toLowerCase();
            const phone = row.find('td:eq(4)').text().toLowerCase();
            const categories = row.find('td:eq(2) .badge').map(function() {
                return $(this).text().toLowerCase();
            }).get();

            const matchesSearch = name.includes(searchText) ||
                                email.includes(searchText) ||
                                phone.includes(searchText) ||
                                categories.some(category => category.includes(searchText));

            const matchesCategory = !selectedCategory ||
                                  categories.includes(categoryFilter.find('option:selected').text().toLowerCase());

            if (matchesSearch && matchesCategory) {
                row.show();
                hasResults = true;
            } else {
                row.hide();
            }
        });

        if (!hasResults) {
            noResultsRow.removeClass('d-none');
            noDataRow.addClass('d-none');
        } else {
            noResultsRow.addClass('d-none');
            noDataRow.addClass('d-none');
        }
    }

    searchInput.on('keyup', filterTable);
    categoryFilter.on('change', filterTable);
});
</script>
@endpush
