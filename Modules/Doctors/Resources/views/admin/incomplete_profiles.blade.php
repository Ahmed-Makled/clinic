@extends('layouts.admin')

@section('title', 'الأطباء - بيانات غير مكتملة')

@section('header_icon')
    <i class="bi bi-exclamation-triangle text-warning me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('doctors.index') }}" class="text-decoration-none">الأطباء</a>
    </li>
    <li class="breadcrumb-item active">بيانات غير مكتملة</li>
@endsection
@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('doctors.update_completion_status') }}" class="btn btn-warning btn-sm px-3">
            <i class="bi bi-arrow-repeat me-1"></i> تحديث حالة الاكتمال
        </a>

    </div>
@endsection


@section('content')
    <div class="card shadow-sm">
        <div class="card-body position-relative">
            <!-- لوحة التنبيه -->
            <div class="alert alert-warning mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-info-circle fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="alert-heading">ملاحظة هامة</h5>
                        <p class="mb-0">هذه القائمة تعرض الأطباء الذين تحتاج بياناتهم إلى استكمال. يرجى استكمال البيانات
                            المطلوبة لكل طبيب لتفعيل حسابه بشكل كامل.</p>
                    </div>
                </div>
            </div>

            <!-- عوامل التصفية -->
            <div class="filters mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="searchInput" class="form-label">اسم الطبيب</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="بحث باسم الطبيب...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="categoryFilter" class="form-label">التخصص</label>
                        <select class="form-select select2" id="categoryFilter">
                            <option value="">جميع التخصصات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="missingDataFilter" class="form-label">البيانات الناقصة</label>
                        <select class="form-select" id="missingDataFilter">
                            <option value="">جميع البيانات الناقصة</option>
                            <option value="صورة الطبيب">صورة الطبيب</option>
                            <option value="التخصصات">التخصصات</option>
                            <option value="جدول المواعيد">جدول المواعيد</option>
                            <option value="رسوم الاستشارة">رسوم الاستشارة</option>
                            <option value="وقت الانتظار">وقت الانتظار</option>
                            <option value="العنوان">العنوان</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- جدول الأطباء -->
            <div class="table-responsive">
                <table class="table table-hover align-middle gs-4">
                    <thead>
                        <tr>
                            <th>الطبيب</th>
                            <th>التخصص</th>
                            <th>البيانات الناقصة</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="doctor-avatar me-3">
                                            @if($doctor->image)
                                                <img src="{{ asset('storage/' . $doctor->image) }}" alt="{{ $doctor->name }}"
                                                    width="50" height="50" class="rounded-circle">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ substr($doctor->name, 0, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $doctor->name }}</div>
                                            <div class="text-muted small">{{ $doctor->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($doctor->category)
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="category-badge category-badge-{{ $doctor->category->id % 6 }}">
                                                {{ $doctor->category->name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-danger fw-semibold small">
                                            <i class="bi bi-exclamation-circle me-1"></i> لا توجد تخصصات
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="missing-data-list">
                                        @if(count($doctor->missing_data) > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($doctor->missing_data as $missing)
                                                    <span class="missing-data-item">
                                                        <i class="bi bi-x-circle text-danger me-1"></i> {{ $missing }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-success">
                                                <i class="bi bi-check-circle"></i> البيانات مكتملة
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $doctor->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('doctors.edit', $doctor) }}?redirect_to={{ route('doctors.incomplete_profiles') }}" class="btn-action btn-edit1"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="استكمال البيانات">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-center">
                                        <i class="bi bi-check-circle text-success display-6 d-block mb-3 opacity-50"></i>
                                        <h5 class="text-success">جميع الأطباء بياناتهم مكتملة!</h5>
                                        <p class="text-muted">لا يوجد أطباء يحتاجون لاستكمال بياناتهم</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ترقيم الصفحات -->
            <div class="d-flex justify-content-between align-items-center mt-4 pagination-wrapper">
                <div class="text-muted small">
                    إجمالي النتائج: {{ $doctors->total() }}
                </div>
                @if($doctors->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($doctors->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $doctors->previousPageUrl() }}" rel="prev">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($doctors->getUrlRange(max($doctors->currentPage() - 2, 1), min($doctors->currentPage() + 2, $doctors->lastPage())) as $page => $url)
                                @if ($page == $doctors->currentPage())
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
                            @if ($doctors->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $doctors->nextPageUrl() }}" rel="next">
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
        </div>
    </div>

    @push('styles')
        <style>
            .avatar-placeholder {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background-color: #e9ecef;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                color: #6c757d;
            }

            .category-badge {
                display: inline-block;
                padding: 0.25rem 0.5rem;
                border-radius: 50px;
                font-size: 0.75rem;
                font-weight: 500;
                line-height: 1;
                white-space: nowrap;
            }

            .category-badge-0 {
                background-color: #E8F5E9;
                color: #2E7D32;
            }

            .category-badge-1 {
                background-color: #E3F2FD;
                color: #1565C0;
            }

            .category-badge-2 {
                background-color: #FFF3E0;
                color: #E65100;
            }

            .category-badge-3 {
                background-color: #F3E5F5;
                color: #7B1FA2;
            }

            .category-badge-4 {
                background-color: #FFEBEE;
                color: #C62828;
            }

            .category-badge-5 {
                background-color: #E8EAF6;
                color: #303F9F;
            }

            .missing-data-list {
                font-size: 0.875rem;
            }

            .missing-data-item {
                padding: 0.15rem 0;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize select2
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });

                // Handle search filter
                const searchInput = document.getElementById('searchInput');
                const categoryFilter = document.getElementById('categoryFilter');
                const missingDataFilter = document.getElementById('missingDataFilter');
                const tableRows = document.querySelectorAll('tbody tr');

                function applyFilters() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const selectedCategory = categoryFilter.value;
                    const selectedMissingData = missingDataFilter.value;

                    tableRows.forEach(row => {
                        const doctorName = row.querySelector('.fw-semibold').textContent.toLowerCase();
                        const doctorEmail = row.querySelector('.text-muted.small')?.textContent.toLowerCase() || '';
                        const categoryElements = Array.from(row.querySelectorAll('.category-badge'));
                        const missingDataElements = Array.from(row.querySelectorAll('.missing-data-item'));

                        const matchesSearch = doctorName.includes(searchTerm) || doctorEmail.includes(searchTerm);

                        const matchesCategory = selectedCategory === '' ||
                            categoryElements.some(el => el.textContent.includes(selectedCategory));

                        const matchesMissingData = selectedMissingData === '' ||
                            missingDataElements.some(el => el.textContent.includes(selectedMissingData));

                        row.style.display = (matchesSearch && matchesCategory && matchesMissingData) ? '' : 'none';
                    });
                }

                searchInput.addEventListener('input', applyFilters);
                categoryFilter.addEventListener('change', applyFilters);
                missingDataFilter.addEventListener('change', applyFilters);
            });
        </script>
    @endpush
@endsection
