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

        <div class="doctors-grid">
            @forelse($doctors as $doctor)
                <div class="doctor-card-container">
                    <article class="doctor-card">
                        <!-- رأس البطاقة: صورة الدكتور والمعلومات الأساسية -->
                        <header class="card-header">
                            <div class="doctor-identity">
                                <div class="avatar-container">
                                    <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" class="doctor-avatar">
                                    <span class="status-badge"></span>
                                </div>
                                <div class="identity-info">
                                    <h3 class="doctor-name">{{ $doctor->title }} {{ $doctor->name }}</h3>
                                    <div class="specialty-badge">
                                        <i class="bi bi-award-fill"></i>
                                        {{ $doctor->categories->pluck('name')->implode(', ') }}
                                    </div>
                                    <div class="rating-info">
                                        <div class="stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($doctor->rating_average))
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif ($i - 0.5 <= $doctor->rating_average)
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </header>
                        @if($doctor->description)
                            <div class="doctor-bio p-3">
                                <p>{{ Str::limit($doctor->description, 100) }}</p>
                            </div>
                        @endif
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

                                <div class="location-info">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>{{ $doctor->governorate->name }} - {{ $doctor->city->name }}</span>
                                </div>
                            </div>


                        </div>
                        <hr class="my-2 border-primary-subtle">

                        <!-- أزرار الإجراءات -->
                        <footer class="d-flex justify-content-between align-items-center card-actions">
                            <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-primary btn-lg rounded-3 btn-sm m-auto w-100 ">
                                <i class="bi bi-calendar-check"></i>
                                <span>احجز موعد</span>
                            </a>
                            {{-- <button type="button" class="btn btn-outline-primary btn-lg rounded-3 btn-sm">
                                <i class="bi bi-telephone-fill"></i>
                                <span>اتصل للحجز</span>
                            </button> --}}
                        </footer>
                    </article>
                </div>
            @empty
                <div class="empty-view">
                    <div class="empty-content">
                        <i class="bi bi-search"></i>
                        <h4>لم يتم العثور على أطباء</h4>
                        <p>لا يوجد أطباء متاحين حالياً</p>
                    </div>
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
            /* التخطيط الرئيسي */
            .doctors-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1.25rem;
                padding: 1rem;
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
                text-align: center;
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

                display: unset;
                gap: 0.375rem;
                padding: unset;
                font-size: unset;
                font-weight: unset;
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
                padding-inline: 1rem;
                padding-top: 0.5rem;
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
                justify-content: center;
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

            /* حالة عدم وجود أطباء */
            .empty-view {
                grid-column: 1 / -1;
                text-align: center;
                padding: 3rem 1rem;
            }

            .empty-content i {
                font-size: 2.5rem;
                color: var(--bs-gray-400);
                margin-bottom: 1rem;
            }

            .empty-content h4 {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--bs-gray-700);
                margin-bottom: 0.5rem;
            }

            .empty-content p {
                font-size: 0.9rem;
                color: var(--bs-gray-600);
                margin: 0;
            }

            /* التجاوب */
            @media (max-width: 768px) {
                .doctors-grid {
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                }

                .doctor-avatar {
                    width: 70px;
                    height: 70px;
                }

                .visit-info {
                    flex-direction: column;
                    gap: 0.5rem;
                }
            }

            @media (max-width: 576px) {
                .doctors-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // No view switching functionality needed anymore
            });
        </script>
    @endpush
@endsection
