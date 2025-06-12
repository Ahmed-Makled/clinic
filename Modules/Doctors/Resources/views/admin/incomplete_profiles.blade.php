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
{{-- @section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('doctors.update_completion_status') }}" class="btn btn-warning btn-sm px-3">
            <i class="bi bi-arrow-repeat me-1"></i> تحديث حالة الاكتمال
        </a>

    </div>
@endsection --}}


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

            @if($doctors->hasPages())
                <div class="pagination-wrapper mt-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="pagination-info">
                                <span class="text-muted">
                                    عرض {{ $doctors->firstItem() }} إلى {{ $doctors->lastItem() }} من أصل {{ $doctors->total() }} نتيجة
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                <ul class="pagination custom-pagination mb-0">
                                    {{-- First Page Link --}}
                                    @if (!$doctors->onFirstPage())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $doctors->url(1) }}" title="الصفحة الأولى">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Previous Page Link --}}
                                    @if ($doctors->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $doctors->previousPageUrl() }}" title="الصفحة السابقة">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $start = max($doctors->currentPage() - 2, 1);
                                        $end = min($doctors->currentPage() + 2, $doctors->lastPage());

                                        $showStartDots = $start > 2;
                                        $showEndDots = $end < $doctors->lastPage() - 1;
                                    @endphp

                                    {{-- Always show first page if not in range --}}
                                    @if ($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $doctors->url(1) }}">1</a>
                                        </li>
                                        @if ($showStartDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $doctors->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $doctors->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Always show last page if not in range --}}
                                    @if ($end < $doctors->lastPage())
                                        @if ($showEndDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $doctors->url($doctors->lastPage()) }}">{{ $doctors->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($doctors->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $doctors->nextPageUrl() }}" title="الصفحة التالية">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-left"></i>
                                            </span>
                                        </li>
                                    @endif

                                    {{-- Last Page Link --}}
                                    @if ($doctors->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $doctors->url($doctors->lastPage()) }}" title="الصفحة الأخيرة">
                                                <i class="bi bi-chevron-double-left"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            @else
                <div class="pagination-wrapper mt-4">
                    <div class="text-center">
                        <span class="text-muted">إجمالي النتائج: {{ $doctors->total() }}</span>
                    </div>
                </div>
            @endif
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

            .pagination-wrapper {
                padding-top: 1.5rem;
                border-top: 1px solid var(--bs-gray-200);
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                border-radius: 0 0 0.5rem 0.5rem;
                padding: 1.5rem;
                margin: 0 -1.5rem -1.5rem -1.5rem;
            }

            .pagination-info {
                font-size: 0.875rem;
                font-weight: 500;
            }

            .custom-pagination {
                margin: 0;
                gap: 0.25rem;
            }

            .custom-pagination .page-item {
                margin: 0;
            }

            .custom-pagination .page-link {
                border: 1px solid var(--bs-gray-300);
                color: var(--bs-gray-700);
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                font-weight: 500;
                border-radius: 0.375rem;
                transition: all 0.15s ease-in-out;
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 2.5rem;
                height: 2.5rem;
                background: white;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .custom-pagination .page-item:first-child .page-link,
            .custom-pagination .page-item:last-child .page-link {
                min-width: 2.5rem;
            }

            .custom-pagination .page-item.active .page-link {
                background: linear-gradient(135deg, var(--bs-primary) 0%, #0056b3 100%);
                border-color: var(--bs-primary);
                color: white;
                box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.3);
                transform: translateY(-1px);
            }

            .custom-pagination .page-link:hover:not(.disabled) {
                background: var(--bs-primary);
                border-color: var(--bs-primary);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.2);
            }

            .custom-pagination .page-item.disabled .page-link {
                background-color: var(--bs-gray-100);
                border-color: var(--bs-gray-200);
                color: var(--bs-gray-400);
                cursor: not-allowed;
                box-shadow: none;
                transform: none;
            }

            .custom-pagination .page-link.dots {
                border: none;
                background: transparent;
                box-shadow: none;
                color: var(--bs-gray-500);
                cursor: default;
                font-weight: 600;
                letter-spacing: 0.1em;
            }

            .custom-pagination .page-item.disabled .page-link.dots {
                background: transparent;
                border: none;
            }

            .custom-pagination .page-link i {
                font-size: 0.75rem;
                line-height: 1;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .pagination-wrapper .row {
                    flex-direction: column-reverse;
                    gap: 1rem;
                }

                .pagination-wrapper .col-md-6 {
                    text-align: center;
                }

                .custom-pagination {
                    justify-content: center;
                    flex-wrap: wrap;
                }

                .custom-pagination .page-link {
                    padding: 0.375rem 0.5rem;
                    min-width: 2rem;
                    height: 2rem;
                    font-size: 0.8rem;
                }

                .pagination-info {
                    font-size: 0.8rem;
                }
            }

            @media (max-width: 576px) {
                .custom-pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
                    display: none;
                }
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
