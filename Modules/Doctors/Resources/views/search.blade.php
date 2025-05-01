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
                            <select name="governorate_id" id="governorate_id" class="form-select form-select-lg border-0 bg-light">
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
                    <div class="doctor-card-container">
                        <article class="doctor-card">
                            <!-- رأس البطاقة: صورة الدكتور والمعلومات الأساسية -->
                            <header class="card-header">
                                <div class="doctor-identity">
                                    <div class="avatar-container">
                                        <img src="{{ $doctor->image ? asset('storage/' . $doctor->image) : asset('images/default-doctor.png') }}"
                                            alt="{{ $doctor->name }}"
                                            class="doctor-avatar">
                                        <span class="status-badge"></span>
                                    </div>
                                    <div class="identity-info">
                                        <h3 class="doctor-name">{{ $doctor->name }}</h3>
                                        <div class="specialty-badge">
                                            <i class="bi bi-award-fill"></i>
                                            {{ $doctor->categories->pluck('name')->implode(', ') }}
                                        </div>
                                    </div>
                                </div>
                            </header>

                            <!-- معلومات الزيارة -->
                            <div class="visit-info">
                                <div class="visit-item fee">
                                    <i class="bi bi-cash-coin"></i>
                                    <div class="info-content">
                                        <span class="value">{{ $doctor->consultation_fee }} جنيه</span>
                                        <label>سعر الكشف</label>
                                    </div>
                                </div>
                                <div class="visit-item duration">
                                    <i class="bi bi-clock-fill"></i>
                                    <div class="info-content">
                                        <span class="value">10 دقيقة</span>
                                        <label>مدة الكشف</label>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات التقييم والموقع -->
                            <div class="details-section">
                                <div class="rating-location">
                                    <div class="rating-info">
                                        <div class="stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($doctor->average_rating))
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif ($i - 0.5 <= $doctor->average_rating)
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="rating-stats">
                                            <strong>{{ number_format($doctor->average_rating, 1) }}</strong>
                                            <span class="count">({{ $doctor->ratings_count }} تقييم)</span>
                                        </div>
                                    </div>
                                    <div class="location-info">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>{{ $doctor->governorate->name }} - {{ $doctor->city->name }}</span>
                                    </div>
                                </div>

                                @if($doctor->description)
                                    <div class="doctor-bio">
                                        <p>{{ Str::limit($doctor->description, 100) }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- أزرار الإجراءات -->
                            <footer class="card-actions">
                                <a href="{{ route('appointments.book', $doctor) }}" class="btn-book">
                                    <i class="bi bi-calendar-check"></i>
                                    <span>احجز موعد</span>
                                </a>
                                <button type="button" class="btn-call">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>اتصل للحجز</span>
                                </button>
                            </footer>
                        </article>
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
/* التخطيط الرئيسي */
.doctors-container {
    transition: all 0.3s ease;
}

.doctors-container.grid-view {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.25rem;
}

/* تصميم البطاقة */
.doctor-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.08);
}

.doctor-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.1);
    border-color: rgba(var(--bs-primary-rgb), 0.2);
}

/* رأس البطاقة */
.card-header {
    padding: 1.25rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.doctor-identity {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.avatar-container {
    position: relative;
    flex-shrink: 0;
}

.doctor-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.1);
}

.status-badge {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 16px;
    height: 16px;
    background: #22c55e;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
}

.identity-info {
    flex-grow: 1;
    min-width: 0;
}

.doctor-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--bs-gray-900);
    margin: 0 0 0.5rem;
}

.specialty-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.35rem 0.75rem;
    background: rgba(var(--bs-primary-rgb), 0.08);
    color: var(--bs-primary);
    border-radius: 20px;
    font-size: 0.8rem;
}

/* معلومات الزيارة */
.visit-info {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: rgba(var(--bs-primary-rgb), 0.03);
}

.visit-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 12px;
    background: white;
}

.visit-item i {
    font-size: 1.2rem;
    color: var(--bs-primary);
}

.info-content {
    display: flex;
    flex-direction: column;
}

.info-content .value {
    font-weight: 600;
    color: var(--bs-gray-900);
    font-size: 0.9rem;
}

.info-content label {
    font-size: 0.75rem;
    color: var(--bs-gray-600);
    margin: 0;
}

/* التقييم والموقع */
.details-section {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.rating-location {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.rating-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stars {
    display: flex;
    gap: 0.2rem;
    color: #fbbf24;
}

.stars i {
    font-size: 0.9rem;
}

.rating-stats {
    font-size: 0.85rem;
    color: var(--bs-gray-600);
}

.rating-stats strong {
    color: var(--bs-gray-900);
    margin-right: 0.2rem;
}

.location-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--bs-gray-700);
}

.location-info i {
    color: #ef4444;
}

/* النبذة */
.doctor-bio {
    padding-top: 0.5rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.doctor-bio p {
    font-size: 0.85rem;
    color: var(--bs-gray-600);
    line-height: 1.5;
    margin: 0;
}

/* الأزرار */
.card-actions {
    margin-top: auto;
    padding: 1rem;
    display: grid;
    gap: 0.75rem;
}

.btn-book,
.btn-call {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none;
    border: none;
    width: 100%;
}

.btn-book {
    background: var(--bs-primary);
    color: white;
}

.btn-book:hover {
    background: var(--bs-primary-dark, #0056b3);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.2);
}

.btn-call {
    background: transparent;
    color: var(--bs-primary);
    border: 1.5px solid var(--bs-primary);
}

.btn-call:hover {
    background: rgba(var(--bs-primary-rgb), 0.08);
    transform: translateY(-1px);
}

/* List View Styles */
.doctors-container.list-view {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.doctors-container.list-view .doctor-card {
    flex-direction: row;
    align-items: center;
    padding: 1.5rem;
    gap: 2rem;
    margin-bottom: 0;
}

.doctors-container.list-view .card-header {
    width: 25%;
    border: none;
    padding: 0;
    margin: 0;
}

.doctors-container.list-view .doctor-identity {
    flex-direction: column;
    text-align: center;
    gap: 0.75rem;
}

.doctors-container.list-view .avatar-container {
    margin: 0 auto;
}

.doctors-container.list-view .doctor-avatar {
    width: 120px;
    height: 120px;
}

.doctors-container.list-view .visit-info {
    width: 20%;
    background: none;
    padding: 0;
    flex-direction: column;
    gap: 1rem;
}

.doctors-container.list-view .visit-item {
    background: rgba(var(--bs-primary-rgb), 0.03);
}

.doctors-container.list-view .details-section {
    width: 35%;
    padding: 0;
    border-right: 1px solid rgba(0, 0, 0, 0.08);
    border-left: 1px solid rgba(0, 0, 0, 0.08);
    padding: 0 2rem;
}

.doctors-container.list-view .card-actions {
    width: 20%;
    padding: 0;
    margin: 0;
}

/* Responsive Layout for List View */
@media (max-width: 1200px) {
    .doctors-container.list-view .doctor-card {
        gap: 1.5rem;
    }

    .doctors-container.list-view .details-section {
        padding: 0 1.5rem;
    }
}

@media (max-width: 992px) {
    .doctors-container.list-view .doctor-card {
        flex-direction: column;
        padding: 1.25rem;
        text-align: center;
    }

    .doctors-container.list-view .card-header,
    .doctors-container.list-view .visit-info,
    .doctors-container.list-view .details-section,
    .doctors-container.list-view .card-actions {
        width: 100%;
        border: none;
        padding: 1rem 0;
    }

    .doctors-container.list-view .details-section {
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .doctors-container.list-view .visit-info {
        flex-direction: row;
        justify-content: center;
        gap: 1rem;
    }

    .doctors-container.list-view .visit-item {
        width: 45%;
    }
}

@media (max-width: 576px) {
    .doctors-container.list-view .visit-info {
        flex-direction: column;
    }

    .doctors-container.list-view .visit-item {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .doctors-container.grid-view {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 576px) {
    .doctors-container.grid-view {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('grid-view-btn');
    const listViewBtn = document.getElementById('list-view-btn');
    const doctorsContainer = document.querySelector('.doctors-container');
    if(!doctorsContainer) return
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
