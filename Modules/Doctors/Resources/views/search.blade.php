@extends('layouts.app')

@section('content')
    <div class="container mt-5 py-5">
        <div class="row">
            <div class="col-12  ">
                  @if (session('success'))
                        <div class="alert-card success mb-4">
                            <div class="alert-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="alert-content">
                                <h6 class="alert-heading">تمت العملية بنجاح!</h6>
                                <p class="mb-0">{!! session('success') !!}</p>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert-card error mb-4">
                            <div class="alert-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="alert-content">
                                <h6 class="alert-heading">حدث خطأ!</h6>
                                <p class="mb-0">{!! session('error') !!}</p>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="alert-card info mb-4">
                            <div class="alert-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div class="alert-content">
                                <h6 class="alert-heading">معلومات!</h6>
                                <p class="mb-0">{!! session('info') !!}</p>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    @endif
            </div>
        </div>
        <div class="row g-4">
            <!-- Search Filters -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 2rem;">
                    <div class="card-body p-3">
                        <h5 class="fw-bold mb-3 ">
                            <i class="bi bi-funnel me-2 text-primary"></i>
                            فلتر البحث
                        </h5>

                        <form id="filter-form" action="{{ route('search') }}" method="GET">
                            <!-- Categories -->
                            <div class="mb-4">
                                <label class="form-label fw-medium mb-2">التخصص</label>
                                <select name="category" class="form-select form-select-lg border-0 bg-light">
                                    <option value="">كل التخصصات</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Governorate -->
                            <div class="mb-4">
                                <label class="form-label fw-medium mb-2">المحافظة</label>
                                <select name="governorate_id" id="governorate_id"
                                    class="form-select form-select-lg border-0 bg-light">
                                    <option value="">كل المحافظات</option>
                                    @foreach($governorates as $governorate)
                                        <option value="{{ $governorate->id }}" {{ request('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                            {{ $governorate->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- City -->
                            <div class="mb-4">
                                <label class="form-label fw-medium mb-2">المدينة</label>
                                <select name="city_id" id="city_id" class="form-select form-select-lg border-0 bg-light">
                                    <option value="">كل المدن</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3">
                                <i class="bi bi-search me-2"></i>
                                بحث
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">نتائج البحث</h4>
                        <p class="text-muted mb-0">تم العثور على {{ $doctors->total() }} طبيب</p>
                    </div>
                    <div class="btn-group view-switcher">
                        <button type="button" class="btn btn-outline-primary active" id="grid-view-btn" data-view="grid">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="list-view-btn" data-view="list">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                </div>

                @if($doctors->count() > 0)
                    <div class="doctors-container list-view">
                        @foreach($doctors as $doctor)
                            <div class="doctor-item">
                                <div class="card h-100 border-0 shadow-sm rounded-4 doctor-card">
                                    <div class="card-body ">
                                        <div class="row align-items-center g-4">
                                            <!-- Doctor Image Column -->
                                            <div class="col-auto">
                                                <div class="doctor-image-wrapper">
                                                    <img src="{{ $doctor->image ? asset('storage/' . $doctor->image) : asset('images/default-doctor.png') }}"
                                                        class="rounded-circle doctor-image" alt="{{ $doctor->name }}">
                                                    <div class="online-indicator"></div>
                                                </div>
                                            </div>

                                            <!-- Doctor Info Column -->
                                            <div class="col">
                                                <div class="d-flex flex-column doctor-info">
                                                    <div class="doctor-header ">
                                                        <h4 class="fw-bold text-primary">{{ $doctor->name }}</h4>
                                                        <div class="rating-text">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= floor($doctor->rating_avg))
                                                                    <i class="bi bi-star-fill text-warning"></i>
                                                                @elseif ($i - 0.5 <= $doctor->rating_avg)
                                                                    <i class="bi bi-star-half text-warning"></i>
                                                                @else
                                                                    <i class="bi bi-star text-warning"></i>
                                                                @endif
                                                            @endfor
                                                            <span class="text-muted">({{ $doctor->ratings_count }} تقييم)</span>
                                                        </div>
                                                    </div>

                                                    <div class="doctor-details">
                                                        <p class="text-muted mb-3 doctor-speciality">
                                                            <i class="bi bi-award-fill text-primary me-2"></i>
                                                            @if($doctor->category)
                                                                 {{ $doctor->category->name }}
                                                                @else
                                                                 غير محدد
                                                                @endif
                                                        </p>

                                                        @if($doctor->description)
                                                            <div class="mb-3" title="{{ $doctor->description }}">
                                                                <p class="text-muted mb-0 doctor-description" >
                                                                    <i class="bi bi-info-circle text-info me-2"></i>
                                                                    {{ Str::limit($doctor->description,28) }}
                                                                </p>
                                                            </div>
                                                        @endif

                                                        <div class="d-flex flex-wrap gap-3   mb-2">


                                                            <div class="d-flex align-items-center ">
                                                                <i class="bi bi-cash-coin text-success me-2"></i>
                                                                <span class="fw-bold">{{ $doctor->consultation_fee }} جنيه</span>
                                                            </div>
                                                            <div class="d-flex align-items-center ">
                                                                <i class="bi bi-clock-fill text-warning me-2"></i>
                                                                <span>{{$doctor->waiting_time}} دقيقة</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                                            <span>
                                                                @if($doctor->governorate)
                                                                    {{ $doctor->governorate->name }}
                                                                    @if($doctor->city)
                                                                        - {{ $doctor->city->name }}
                                                                    @endif
                                                                @else
                                                                    غير محدد
                                                                @endif
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <hr class="my-3 border-primary-subtle">

                                                    <div class="d-flex justify-content-between align-items-center">


                                                        <div class="w-100">
                                                            <a href="{{ route('appointments.book', $doctor) }}"
                                                                class="btn btn-primary w-100 rounded-3 btn-sm  ">
                                                                <i class="bi bi-calendar-check me-2"></i>
                                                                احجز موعد
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($doctors->hasPages())
                        <div class="pagination-wrapper mt-5">
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
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-search text-primary mb-4" style="font-size: 4rem;"></i>
                        <h4 class="fw-bold mb-3">لم يتم العثور على نتائج</h4>
                        <p class="text-muted">جرب تغيير معايير البحث الخاصة بك</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .shadow-hover {
                transition: all 0.3s ease;
            }

            .shadow-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 1rem 3rem rgba(0, 0, 0, .08) !important;
            }

            .doctor-image {
                height: 220px;
                object-fit: cover;
                transition: all 0.3s ease;
            }



            .bg-primary-subtle {
                background-color: rgba(var(--bs-primary-rgb), 0.1);
            }

            .form-select:focus {
                box-shadow: none;
                border-color: var(--bs-primary);
            }

            .form-select-lg {
                font-size: 1rem;
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            .pagination {
                justify-content: center;
                gap: 0.5rem;
            }

            .page-link {
                border-radius: 0.5rem;
                border: none;
                padding: 0.5rem 1rem;
                color: var(--bs-primary);
            }

            .page-item.active .page-link {
                background-color: var(--bs-primary);
                color: white;
            }

            .doctor-card {
                transition: all 0.3s ease-in-out;
                cursor: pointer;
                overflow: hidden;
            }

            .doctor-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 1rem 2rem rgba(0, 0, 0, .12) !important;
            }

            .doctor-image-wrapper {
                position: relative;
                width: 120px;
                height: 120px;
                border-radius: 50%;
                border: 3px solid var(--bs-primary);
                padding: 3px;
            }

            .doctor-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
                transition: transform 0.3s ease;
            }


            .online-indicator {
                position: absolute;
                bottom: 16px;
                right: 5px;
                width: 16px;
                height: 16px;
                background-color: #22c55e;
                border-radius: 50%;
                border: 2px solid white;
                box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
            }


            .doctor-speciality {
                font-size: 1.1rem;
                color: var(--bs-gray-700);
            }

            .book-btn {
                transition: all 0.3s ease;
                border: none;
            }

            .book-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
            }

            /* Add smooth hover animation for rating badge */
            .badge {
                transition: all 0.2s ease;
            }

            .badge:hover {
                transform: scale(1.05);
            }

            /* Add a subtle line separator */
            hr.border-primary-subtle {
                opacity: 0.15;
                margin: 1.5rem 0;
            }

            /* Make text colors more vibrant */
            .text-primary {
                color: #0d6efd !important;
            }

            .text-success {
                color: #198754 !important;
            }

            .text-warning {
                color: #ffc107 !important;
            }

            .text-danger {
                color: #dc3545 !important;
            }

            /* Improve responsive layout */
            @media (max-width: 768px) {
                .doctor-image-wrapper {
                    width: 100px;
                    height: 100px;
                }


                .book-btn {
                    width: 100%;
                    margin-top: 1rem;
                }
            }

            .doctor-description {
                font-size: 1rem;
                line-height: 1.6;
                color: var(--bs-gray-600);
                padding: 0.5rem 1rem;
                background-color: var(--bs-light);
                border-radius: 8px;
                margin: 0.5rem 0;
                text-align: start;
            }

            .doctor-description i {
                font-size: 1.1rem;
            }

            /* Add text truncation for long descriptions */
            @media (max-width: 768px) {
                .doctor-description {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
            }

            .rating-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                background: linear-gradient(145deg, var(--bs-primary-subtle), var(--bs-light));
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                font-weight: 600;
                font-size: 1.1rem;
                color: var(--bs-primary);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }


            .rating-badge i {
                color: #ffc107;
                font-size: 1.2rem;
                animation: star-pulse 2s infinite;
            }

            .rating-score {
                color: var(--bs-primary);
                font-weight: 700;
            }

            .rating-count {
                position: relative;
                cursor: pointer;
                padding: 0.3rem 0.6rem;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
            }

            .rating-details {
                display: none;
                position: absolute;
                top: calc(100% + 10px);
                left: 50%;
                transform: translateX(-50%);
                background-color: white;
                padding: 1.2rem;
                border-radius: 1rem;
                box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15);
                z-index: 10;
                width: 250px;
                border: 1px solid var(--bs-primary-subtle);
            }

            .rating-details:before {
                content: '';
                position: absolute;
                top: -8px;
                left: 50%;
                transform: translateX(-50%);
                border-left: 8px solid transparent;
                border-right: 8px solid transparent;
                border-bottom: 8px solid white;
            }


            .progress-container {
                display: flex;
                align-items: center;
                gap: 0.8rem;
                margin-bottom: 0.8rem;
            }

            .progress-container:last-child {
                margin-bottom: 0;
            }

            .progress-label {
                flex: 0 0 2rem;
                font-weight: 600;
                color: var(--bs-gray-700);
                display: flex;
                align-items: center;
                gap: 0.3rem;
            }

            .progress {
                flex: 1;
                background-color: var(--bs-light);
                border-radius: 1rem;
                overflow: hidden;
            }

            .progress-bar {
                transition: width 1s ease;
                background: linear-gradient(90deg, #ffc107, #ffcd39);
            }

            .progress-value {
                flex: 0 0 3rem;
                text-align: right;
                font-weight: 500;
                color: var(--bs-gray-600);
            }

            @keyframes star-pulse {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.2);
                }

                100% {
                    transform: scale(1);
                }
            }

            /* تحسين التصميم على الشاشات الصغيرة */
            @media (max-width: 768px) {
                .rating-badge {
                    font-size: 1rem;
                    padding: 0.4rem 0.8rem;
                }

                .rating-details {
                    width: 200px;
                    padding: 1rem;
                }

                .progress-container {
                    gap: 0.5rem;
                    margin-bottom: 0.5rem;
                }
            }

            .rating-text {
                display: inline-flex;
                align-items: center;
                gap: 0.2rem;
                font-size: 1.1rem;
            }

            .rating-text i {
                color: #ffc107;
            }

            .rating-text .rating-score {
                font-weight: 600;
                color: #0d6efd;
            }

            .rating-text .text-muted {
                font-size: 0.95rem;
            }

            @media (max-width: 768px) {
                .rating-text {
                    font-size: 1rem;
                }

                .rating-text .text-muted {
                    font-size: 0.9rem;
                }
            }

            /* View Switcher Styles */
            .view-switcher .btn {
                padding: 0.5rem 1rem;
                transition: all 0.3s ease;
            }

            .view-switcher .btn i {
                font-size: 1.2rem;
            }

            .view-switcher .btn.active {
                background-color: var(--bs-primary);
                color: white;
            }

            /* Grid View Styles */
            .doctors-container.grid-view {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }

            .doctors-container.grid-view .col-12 {
                width: 50%;
            }

            .doctors-container.grid-view .doctor-card {
                height: 100%;
            }

            .doctors-container.grid-view .row {
                flex-direction: column;
                text-align: center
            }

            /* List View Styles */
            .doctors-container.list-view {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            .doctors-container.list-view .col-12 {
                width: 100%;
            }

            @media (max-width: 768px) {
                .doctor-image-wrapper {
                    width: 100px;
                    height: 100px;
                }


                .book-btn {
                    width: 100%;
                    margin-top: 1rem;
                }
            }

            .doctor-description {
                font-size: 1rem;
                line-height: 1.6;
                color: var(--bs-gray-600);
                padding: 0.5rem 1rem;
                background-color: var(--bs-light);
                border-radius: 8px;
                margin: 0.5rem 0;
                text-align: start;
            }

            .doctor-description i {
                font-size: 1.1rem;
            }

            /* Add text truncation for long descriptions */
            @media (max-width: 768px) {
                .doctor-description {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
            }

            .rating-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                background: linear-gradient(145deg, var(--bs-primary-subtle), var(--bs-light));
                padding: 0.5rem 1rem;
                border-radius: 1rem;
                font-weight: 600;
                font-size: 1.1rem;
                color: var(--bs-primary);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }


            .rating-badge i {
                color: #ffc107;
                font-size: 1.2rem;
                animation: star-pulse 2s infinite;
            }

            .rating-score {
                color: var(--bs-primary);
                font-weight: 700;
            }

            .rating-count {
                position: relative;
                cursor: pointer;
                padding: 0.3rem 0.6rem;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
            }

            .rating-details {
                display: none;
                position: absolute;
                top: calc(100% + 10px);
                left: 50%;
                transform: translateX(-50%);
                background-color: white;
                padding: 1.2rem;
                border-radius: 1rem;
                box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15);
                z-index: 10;
                width: 250px;
                border: 1px solid var(--bs-primary-subtle);
            }

            .rating-details:before {
                content: '';
                position: absolute;
                top: -8px;
                left: 50%;
                transform: translateX(-50%);
                border-left: 8px solid transparent;
                border-right: 8px solid transparent;
                border-bottom: 8px solid white;
            }


            .progress-container {
                display: flex;
                align-items: center;
                gap: 0.8rem;
                margin-bottom: 0.8rem;
            }

            .progress-container:last-child {
                margin-bottom: 0;
            }

            .progress-label {
                flex: 0 0 2rem;
                font-weight: 600;
                color: var(--bs-gray-700);
                display: flex;
                align-items: center;
                gap: 0.3rem;
            }

            .progress {
                flex: 1;
                background-color: var(--bs-light);
                border-radius: 1rem;
                overflow: hidden;
            }

            .progress-bar {
                transition: width 1s ease;
                background: linear-gradient(90deg, #ffc107, #ffcd39);
            }

            .progress-value {
                flex: 0 0 3rem;
                text-align: right;
                font-weight: 500;
                color: var(--bs-gray-600);
            }

            @keyframes star-pulse {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.2);
                }

                100% {
                    transform: scale(1);
                }
            }

            /* تحسين التصميم على الشاشات الصغيرة */
            @media (max-width: 768px) {
                .rating-badge {
                    font-size: 1rem;
                    padding: 0.4rem 0.8rem;
                }

                .rating-details {
                    width: 200px;
                    padding: 1rem;
                }

                .progress-container {
                    gap: 0.5rem;
                    margin-bottom: 0.5rem;
                }
            }

            .rating-text {
                display: inline-flex;
                align-items: center;
                gap: 0.2rem;
                font-size: 1.1rem;
            }

            .rating-text i {
                color: #ffc107;
            }

            .rating-text .rating-score {
                font-weight: 600;
                color: #0d6efd;
            }

            .rating-text .text-muted {
                font-size: 0.95rem;
            }

            @media (max-width: 768px) {
                .rating-text {
                    font-size: 1rem;
                }

                .rating-text .text-muted {
                    font-size: 0.9rem;
                }
            }

            /* View Switcher Styles */
            .view-switcher .btn {
                padding: 0.5rem 1rem;
                transition: all 0.3s ease;
            }

            .view-switcher .btn i {
                font-size: 1.2rem;
            }

            .view-switcher .btn.active {
                background-color: var(--bs-primary);
                color: white;
            }

            /* Grid View Styles */
            .doctors-container.grid-view {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }

            .doctors-container.grid-view .col-12 {
                width: 50%;
            }

            .doctors-container.grid-view .doctor-card {
                height: 100%;
            }

            .doctors-container.grid-view .row {
                flex-direction: column;
                text-align: center
            }

            /* List View Styles */
            .doctors-container.list-view {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            .doctors-container.list-view .col-12 {
                width: 100%;
            }

            .pagination-wrapper {
                padding-top: 1.5rem;
                border-top: 1px solid var(--bs-gray-200);
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                border-radius: 0.5rem;
                padding: 1.5rem;
                margin-top: 2rem;
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

            /* ...existing code... */
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const governorateSelect = document.getElementById('governorate_id');
                const citySelect = document.getElementById('city_id');
                const selectedCityId = '{{ request('city_id') }}';
                const selectedGovernorateId = '{{ request('governorate_id') }}';
                const governorates = @json($governorates);

                // Load all cities from governorates data
                function loadAllCities() {
                    citySelect.innerHTML = '<option value="">كل المدن</option>';

                    governorates.forEach(governorate => {
                        if (governorate.cities) {
                            governorate.cities.forEach(city => {
                                const selected = city.id == selectedCityId ? 'selected' : '';
                                citySelect.innerHTML += `<option value="${city.id}" ${selected}>${city.name}</option>`;
                            });
                        }
                    });
                }

                async function loadCities(governorateId) {
                    if (!governorateId) {
                        // If no governorate selected, show all cities
                        loadAllCities();
                        return;
                    }

                    try {
                        const response = await fetch(`/governorates/${governorateId}/cities`);
                        const cities = await response.json();

                        citySelect.innerHTML = '<option value="">كل المدن</option>';
                        cities.forEach(city => {
                            const selected = city.id == selectedCityId ? 'selected' : '';
                            citySelect.innerHTML += `<option value="${city.id}" ${selected}>${city.name}</option>`;
                        });
                    } catch (error) {
                        console.error('Error loading cities:', error);
                        // Fallback to loading all cities
                        loadAllCities();
                    }
                }

                // Initialize cities based on current state
                if (selectedGovernorateId) {
                    // If governorate is selected, load its cities
                    loadCities(selectedGovernorateId);
                } else {
                    // Load all cities by default (including when only city is selected)
                    loadAllCities();
                }

                governorateSelect.addEventListener('change', (e) => loadCities(e.target.value));
            });
        </script>

        <script>
            // View Switcher Functionality
            document.addEventListener('DOMContentLoaded', function () {
                const gridViewBtn = document.getElementById('grid-view-btn');
                const listViewBtn = document.getElementById('list-view-btn');
                const doctorsContainer = document.querySelector('.doctors-container');
                if (!doctorsContainer) return
                // Load saved view preference from localStorage
                const savedView = localStorage.getItem('doctorsViewPreference') || 'grid';

                // Initial view setup without animation
                doctorsContainer.classList.remove('grid-view', 'list-view');
                doctorsContainer.classList.add(`${savedView}-view`);
                gridViewBtn.classList.toggle('active', savedView === 'grid');
                listViewBtn.classList.toggle('active', savedView === 'list');

                gridViewBtn.addEventListener('click', () => setViewMode('grid'));
                listViewBtn.addEventListener('click', () => setViewMode('list'));

                function setViewMode(mode) {
                    // Fade out
                    doctorsContainer.style.opacity = '0';

                    // Switch view after a short delay
                    setTimeout(() => {
                        doctorsContainer.classList.remove('grid-view', 'list-view');
                        doctorsContainer.classList.add(`${mode}-view`);

                        // Update buttons active state
                        gridViewBtn.classList.toggle('active', mode === 'grid');
                        listViewBtn.classList.toggle('active', mode === 'list');

                        // Fade back in
                        doctorsContainer.style.opacity = '1';

                        // Save preference
                        localStorage.setItem('doctorsViewPreference', mode);
                    }, 150);
                }
            });
        </script>
    @endpush
@endsection
