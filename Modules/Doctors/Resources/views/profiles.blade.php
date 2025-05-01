@extends('layouts.app')

@section('content')
    <div class="container mt-5 py-5">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">أطباؤنا المتميزون</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="doctors-container grid-view">
            @forelse($doctors as $doctor)
            <div class="doctor-item">
                <div class="card h-100 border-0 shadow-hover rounded-3 doctor-card">
                    <!-- Doctor Header Section -->
                    <div class="doctor-header position-relative overflow-hidden">
                        <div class="header-bg rounded-top-3"></div>
                        <div class="doctor-header-content text-center position-relative">
                            <div class="doctor-image-container">
                                <div class="doctor-image-wrapper mx-auto">
                                    <img src="{{ $doctor->image_url }}"
                                        class="rounded-circle doctor-image"
                                        alt="{{ $doctor->name }}"
                                    >
                                    <div class="online-indicator pulse"></div>
                                </div>
                            </div>
                            <h4 class="doctor-name">{{ $doctor->title }} {{ $doctor->name }}</h4>
                            <div class="specialty-badge">
                                <i class="bi bi-award-fill"></i>
                                {{ $doctor->specialty ? $doctor->specialty->name : 'غير محدد' }}
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <!-- Rating Section -->
                        <div class="rating-section text-center mb-4">
                            <div class="stars-container">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($doctor->rating_average))
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @elseif ($i - 0.5 <= $doctor->rating_average)
                                        <i class="bi bi-star-half text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="rating-info">
                                <span class="rating-score">{{ number_format($doctor->rating_average, 1) }}</span>
                                <span class="rating-count">({{ $doctor->ratings_count }} تقييم)</span>
                            </div>
                        </div>

                        <!-- Doctor Info Cards -->
                        <div class="info-cards-grid mb-4">
                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">العنوان</span>
                                    <span class="info-value">{{ $doctor->governorate->name }} - {{ $doctor->city->name }}</span>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">سعر الكشف</span>
                                    <span class="info-value price">{{ $doctor->consultation_fee }} جنيه</span>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">مدة الكشف</span>
                                    <span class="info-value">10 دقيقة</span>
                                </div>
                            </div>
                        </div>

                        @if($doctor->description)
                        <div class="description-section mb-4">
                            <h6 class="section-title">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                نبذة عن الطبيب
                            </h6>
                            <p class="description-text">
                                {{ $doctor->description }}
                            </p>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('doctors.show', $doctor->id) }}"
                               class="btn btn-primary btn-book w-100 mb-2">
                                <i class="bi bi-calendar-check me-2"></i>
                                احجز موعد
                            </a>
                            <button class="btn btn-outline-primary btn-call w-100">
                                <i class="bi bi-telephone-fill me-2"></i>
                                اتصل للحجز
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="bi bi-search"></i>
                    </div>
                    <h4 class="empty-state-title">لم يتم العثور على أطباء</h4>
                    <p class="empty-state-description">لا يوجد أطباء متاحين حالياً</p>
                </div>
            @endforelse
        </div>

        @if($doctors->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>

@push('styles')
<style>
/* Grid Layout */
.doctors-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 0.75rem;
}

/* Card Styling */
.doctor-card {
    background: white;
    transition: all 0.3s ease;
    position: relative;
}

.shadow-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1)!important;
}

/* Header Styling */
.doctor-header {
    padding-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.header-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(135deg, var(--bs-primary) 0%, #4a90e2 100%);
    opacity: 0.1;
}

.doctor-header-content {
    position: relative;
    z-index: 1;
}

/* Doctor Image */
.doctor-image-container {
    margin-bottom: 0.75rem;
}

.doctor-image-wrapper {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    padding: 3px;
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    position: relative;
    border: 2px solid var(--bs-primary);
}

.doctor-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

/* Online Indicator */
.online-indicator {
    position: absolute;
    bottom: 6px;
    right: 6px;
    width: 10px;
    height: 10px;
    background-color: #22c55e;
    border-radius: 50%;
    border: 2px solid white;
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
    }
}

/* Doctor Name */
.doctor-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--bs-primary);
    margin: 0.4rem 0;
}

/* Specialty Badge */
.specialty-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    border-radius: 20px;
    color: var(--bs-primary);
    font-size: 0.8rem;
    margin-bottom: 0.75rem;
}

.specialty-badge i {
    margin-left: 0.5rem;
}

/* Rating Section */
.rating-section {
    padding: 0.75rem;
    background-color: rgba(var(--bs-warning-rgb), 0.1);
    border-radius: 12px;
    margin: 0.75rem 0;
}

.stars-container {
    margin-bottom: 0.4rem;
}

.stars-container i {
    font-size: 1rem;
    margin: 0 0.1rem;
}

.rating-info {
    font-size: 0.8rem;
}

.rating-score {
    font-weight: 700;
    color: var(--bs-primary);
    margin-right: 0.3rem;
}

.rating-count {
    color: var(--bs-gray-600);
}

/* Info Cards Grid */
.info-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}

.info-card {
    padding: 0.75rem;
    background-color: var(--bs-light);
    border-radius: 12px;
    text-align: center;
    transition: all 0.3s ease;
}

.info-card:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    transform: translateY(-2px);
}

.info-icon {
    font-size: 1.2rem;
    margin-bottom: 0.4rem;
}

.info-icon i {
    color: var(--bs-primary);
}

.info-content {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.75rem;
    color: var(--bs-gray-600);
    margin-bottom: 0.2rem;
}

.info-value {
    font-weight: 600;
    color: var(--bs-gray-800);
    font-size: 0.85rem;
}

.info-value.price {
    color: var(--bs-success);
}

/* Description Section */
.description-section {
    background-color: var(--bs-light);
    padding: 0.75rem;
    border-radius: 12px;
}

.section-title {
    color: var(--bs-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.description-text {
    color: var(--bs-gray-700);
    font-size: 0.85rem;
    line-height: 1.5;
    margin: 0;
}

/* Action Buttons */
.action-buttons {
    margin-top: 1rem;
}

.btn-book {
    padding: 0.6rem 1.2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-book:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
}

.btn-call {
    padding: 0.6rem 1.2rem;
    font-weight: 600;
}

.btn-call:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
}

/* Empty State */
.empty-state {
    width: 100%;
    padding: 2rem;
}

.empty-state-icon {
    font-size: 3rem;
    color: var(--bs-primary);
    opacity: 0.5;
}

.empty-state-title {
    color: var(--bs-gray-800);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-state-description {
    color: var(--bs-gray-600);
    max-width: 300px;
    margin: 0 auto;
}

/* Responsive Design */
@media (max-width: 992px) {
    .doctors-container {
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    }
}

@media (max-width: 768px) {
    .info-cards-grid {
        grid-template-columns: 1fr;
    }

    .doctor-image-wrapper {
        width: 100px;
        height: 100px;
    }

    .doctor-name {
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .doctors-container {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // No view switching functionality needed anymore
});
</script>
@endpush
@endsection
