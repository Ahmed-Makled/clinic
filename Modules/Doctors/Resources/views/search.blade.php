@extends('layouts.app')

@section('content')
    <div class="container mt-5 py-5">
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
                                                                @if ($i <= floor($doctor->average_rating))
                                                                    <i class="bi bi-star-fill text-warning"></i>
                                                                @elseif ($i - 0.5 <= $doctor->average_rating)
                                                                    <i class="bi bi-star-half text-warning"></i>
                                                                @else
                                                                    <i class="bi bi-star text-warning"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>

                                                    <div class="doctor-details">
                                                        <p class="text-muted mb-3 doctor-speciality">
                                                            <i class="bi bi-award-fill text-primary me-2"></i>
                                                                 {{ $doctor->categories->pluck('name')->implode(', ') }}
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
                                                                <span>10 دقيقة</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                                            <span>{{ $doctor->governorate->name }} -
                                                                {{ $doctor->city->name }}</span>
                                                        </div>

                                                    </div>

                                                    <hr class="my-3 border-primary-subtle">

                                                    <div class="d-flex justify-content-between align-items-center">

                                                        <div>
                                                            <a
                                                                class="btn btn-outline-primary btn-lg rounded-3 btn-sm">
                                                                <i class="bi bi-calendar-check me-2"></i>
                                                                اتصل احجز
                                                            </a>
                                                        </div>

                                                        <div>
                                                            <a href="{{ route('appointments.book', $doctor) }}"
                                                                class="btn btn-primary btn-lg rounded-3 btn-sm ">
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

                    <div class="mt-5">
                        {{ $doctors->withQueryString()->links() }}
                    </div>
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
                box-shadow: 0 2px 4px rgba(13, 110, 253, 0.1);
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

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translate(-50%, -10px);
                }

                to {
                    opacity: 1;
                    transform: translate(-50%, 0);
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

                .doctors-container.grid-view .row.align-items-center,
                .doctors-container.list-view .row.align-items-center {
                    flex-direction: column;
                    text-align: center;
                }


            }

            /* Responsive Styles */
            @media (max-width: 1200px) {
                .doctors-container.grid-view {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 992px) {
                .doctors-container.grid-view {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 768px) {
                .doctors-container.grid-view {
                    grid-template-columns: 1fr;
                }

                /* View Switcher Styles */
                .doctors-container {
                    transition: all 0.3s ease;
                }

                /* Grid View Styles */
                .doctors-container.grid-view {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 1.5rem;
                }

                .doctors-container.grid-view .col-12 {
                    width: 100%;
                    padding: 0;
                }

                .doctors-container.grid-view .card-body {
                    flex-direction: column;
                }

                .doctors-container.grid-view .row.align-items-center {
                    flex-direction: column;
                    text-align: center;
                }

                .doctors-container.grid-view .doctor-image-wrapper {
                    width: 150px;
                    height: 150px;
                }

                .doctors-container.grid-view .doctor-info {
                    width: 100%;
                }

                /* List View Styles */
                .doctors-container.list-view {
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
                }

                .doctors-container.list-view .col-12 {
                    width: 100%;
                    padding: 0;
                }

                .doctors-container.list-view .row.align-items-center {
                    flex-direction: row;
                }

                .doctors-container.list-view .doctor-image-wrapper {
                    width: 120px;
                    height: 120px;
                }

                /* Responsive Styles */
                @media (max-width: 992px) {
                    .doctors-container.grid-view {
                        grid-template-columns: 1fr;
                    }
                }

                @media (max-width: 768px) {

                    .doctors-container.grid-view .row.align-items-center,
                    .doctors-container.list-view .row.align-items-center {
                        flex-direction: column;
                        text-align: center;
                    }

                }

                /* Additional Grid View Styles */
                .doctors-container.grid-view .doctor-info {
                    text-align: center;
                }

                .doctors-container.grid-view .d-flex.justify-content-between {
                    flex-direction: column;
                    gap: 1rem;
                }

                .doctors-container.grid-view .d-flex.flex-wrap.gap-4 {
                    justify-content: center;
                }


                /* Additional List View Styles */
                .doctors-container.list-view .d-flex.justify-content-between {
                    flex-direction: row;
                }


                .doctors-container.list-view .doctor-speciality,
                .doctors-container.list-view .doctor-description,
                .doctors-container.list-view .text-muted {
                    text-align: start;
                }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const governorateSelect = document.getElementById('governorate_id');
                const citySelect = document.getElementById('city_id');
                const selectedCityId = '{{ request('city_id') }}';

                async function loadCities(governorateId) {
                    if (!governorateId) {
                        citySelect.innerHTML = '<option value="">كل المدن</option>';
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
                    }
                }

                if (governorateSelect.value) {
                    loadCities(governorateSelect.value);
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
                setViewMode(savedView);

                gridViewBtn.addEventListener('click', () => setViewMode('grid'));
                listViewBtn.addEventListener('click', () => setViewMode('list'));

                function setViewMode(mode) {

                    doctorsContainer.classList.remove('grid-view', 'list-view');
                    doctorsContainer.classList.add(`${mode}-view`);

                    // Update buttons active state
                    gridViewBtn.classList.toggle('active', mode === 'grid');
                    listViewBtn.classList.toggle('active', mode === 'list');

                    // Save preference
                    localStorage.setItem('doctorsViewPreference', mode);
                }
            });
        </script>
    @endpush
@endsection
