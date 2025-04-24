@extends('admin::layouts.master')

@section('title', 'التخصصات')

@section('actions')
    <a href="{{ route('admin.specialties.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة تخصص جديد
    </a>
@endsection

@section('content')
<div class="modern-table-container">
    <div class="table-controls">
        <div class="control-item">
            <input type="text"
                   class="search-input"
                   id="searchInput"
                   placeholder="ابحث عن تخصص...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم التخصص</th>
                    <th>عدد الأطباء</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($specialties as $specialty)
                    <tr>
                        <td>{{ $specialty->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($specialty->image)
                                    <img src="{{ asset('storage/' . $specialty->image) }}"
                                         alt="{{ $specialty->name }}"
                                         class="rounded-circle me-2"
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                @endif
                                {{ $specialty->name }}
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-badge-pending">
                                {{ $specialty->doctors_count ?? 0 }} طبيب
                            </span>
                        </td>
                        <td>{{ Str::limit($specialty->description, 50) }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.specialties.edit', $specialty) }}"
                                   class="btn-action btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.specialties.destroy', $specialty) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action btn-delete delete-confirmation">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-list-check empty-state-icon"></i>
                                <p class="empty-state-text">لا توجد تخصصات</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $specialties->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // البحث المباشر
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('.modern-table tbody tr');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // تأكيد الحذف
    document.querySelectorAll('.delete-confirmation').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا التخصص؟')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush

@endsection
