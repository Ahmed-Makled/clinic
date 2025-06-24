@extends('layouts.app')

@section('content')
    <div class="container py-5 mt-5 overflow-hidden ">
        <div class="row g-4">
            <!-- Doctor Profile Section -->
            <div class="col-md-7">
                <div class="border bg-white rounded-4 shadow-sm overflow-hidden h-100">
                    <!-- Cover Image & Profile Header -->
                    <div class="doctor-cover position-relative">
                        <div class="cover-image"></div>
                        <div class="profile-header text-center">
                            <div class="doctor-image-container">
                                @if($doctor->image)
                                    <img src="{{ Storage::url($doctor->image) }}"
                                         onerror="this.onerror=null; this.src='{{ asset('images/default-doctor.png') }}';"
                                         alt="{{ $doctor->name }}"
                                         class="doctor-avatar">
                                @else
                                    <img src="{{ asset('images/default-doctor.png') }}"
                                         alt="صورة افتراضية"
                                         class="doctor-avatar">
                                @endif
                                <div class="online-indicator"></div>
                            </div>

                            <div class="doctor-basic-info p-4">
                                <h3 class="doctor-name">{{ $doctor->name }}</h3>
                                <div class="">
                                    <span class="  text-primary d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-award-fill"></i>
                                        {{ $doctor->category ? $doctor->category->name : 'غير محدد' }}
                                    </span>
                                </div>
                                <div class="location-badge">
                                    <span class=" text-dark d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-geo-alt-fill text-danger"></i>
                                        {{ $doctor->governorate->name }} - {{ $doctor->city->name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="doctor-details p-4">
                        <!-- Quick Info Cards -->
                        <div class="info-cards row g-3 mb-4">
                            <div class="col-4">
                                <div class="info-card success">
                                    <div class="info-card-icon">
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <div class="info-card-value">{{ number_format($doctor->rating, 1) }}</div>
                                        <div class="info-card-label">التقييم العام</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="info-card primary">
                                    <div class="info-card-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <div class="info-card-value">{{ $doctor->patients_count ?? 0 }}</div>
                                        <div class="info-card-label">مريض</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="info-card warning">
                                    <div class="info-card-icon">
                                        <i class="bi bi-calendar2-check-fill"></i>
                                    </div>
                                    <div class="info-card-content">
                                        <div class="info-card-value">{{ $doctor->experience_years ?? 0 }}</div>
                                        <div class="info-card-label">سنين خبرة</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Description -->
                        <div class="doctor-section mb-4">
                            <h5 class="section-title">
                                <i class="bi bi-person-vcard-fill text-primary me-2"></i>
                                نبذة عن الطبيب
                            </h5>
                            <div class="section-content">
                                <p class="description mb-0">{{ $doctor->description }}</p>
                            </div>
                        </div>


                        <!-- Rating Details -->
                        <div class="doctor-section">
                            <h5 class="section-title">
                                <i class="bi bi-star-half text-primary me-2"></i>
                                تقييمات المرضى
                            </h5>
                            <div class="section-content">
                                <div class="rating-overview">
                                    {{-- <div class="rating-summary text-center mb-4">
                                        <div class="rating-score">{{ number_format($doctor->rating, 1) }}</div>
                                        <div class="rating-stars">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < floor($doctor->rating))
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @elseif ($i < $doctor->rating)
                                                    <i class="bi bi-star-half text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="rating-count text-muted">
                                            {{ $doctor->ratings_count ?? 0 }} تقييم
                                        </div>
                                    </div> --}}

                                    <div class="rating-bars">
                                        @for ($i = 5; $i >= 1; $i--)
                                            @php
                                                $percentage = $doctor->ratings_count ?
                                                    (collect($doctor->ratings)->where('rating', $i)->count() / $doctor->ratings_count) * 100 : 0;
                                            @endphp
                                            <div class="rating-bar-item">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rating-label">
                                                        {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                                                    </div>
                                                    <div class="rating-progress flex-grow-1">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-warning"
                                                                 role="progressbar"
                                                                 style="width: {{ $percentage }}%"
                                                                 aria-valuenow="{{ $percentage }}"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="rating-percentage">
                                                        {{ number_format($percentage) }}%
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Section -->
            <div class="col-md-5">
                <div class="border bg-white p-4 rounded-3 shadow-sm h-100">
                    <h5 class="text-center mb-4 pb-3 border-bottom fw-bold">معلومات الحجز</h5>

                    <div class="booking-info mb-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-cash-coin text-success me-2" style="font-size: 1.5rem"></i>
                                    <div>
                                        <small class="text-muted d-block">سعر الكشف</small>
                                        <strong>{{ $doctor->consultation_fee }} ج م</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock text-primary me-2" style="font-size: 1.5rem"></i>
                                    <div>
                                        <small class="text-muted d-block">مدة الإنتظار</small>
                                        <strong>{{ $doctor->waiting_time ?? 30 }} دقيقة</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="location-info alert alert-light mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt text-danger me-2" style="font-size: 1.2rem"></i>
                            <div>
                                <small class="text-muted">سيتم إرسال العنوان التفصيلي ورقم العيادة بعد تأكيد الحجز</small>
                            </div>
                        </div>
                    </div>

                        @if (session('warning'))
                            <div class="alert alert-warning" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                                    <div>
                                        <strong>تنبيه:</strong> {{ session('warning') }}
                                        @if (session('profile_required'))
                                            <div class="mt-2">
                                                <a href="{{ route('profile') }}?redirect_to={{ route('appointments.book', session('doctor_id')) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-person-plus me-1"></i>
                                                    إكمال الملف الشخصي
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                    <div class="booking-section">
                        <div class="section-header text-center mb-4">
                            <h5 class="fw-bold mb-2">اختر حجز الحجز</h5>
                            <p class="text-muted small">اختر التاريخ والوقت المناسب لحجزك</p>
                        </div>

                        <form id='bookingForm'  action="{{ route('appointments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                            <input type="hidden" name="appointment_date" value="{{ $selectedDate }}">

                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                                        <strong>يرجى تصحيح الأخطاء التالية:</strong>
                                    </div>
                                    <ul class="list-unstyled m-0">
                                        @foreach ($errors->all() as $error)
                                            <li class="d-flex align-items-center text-danger">
                                                <i class="bi bi-dot me-2"></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Date Selection -->
                            <div class="date-selection mb-4">
                                <label class="form-label fw-medium d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-calendar3 text-primary"></i>
                                    اختر التاريخ المناسب
                                </label>
                                <div class="date-carousel">
                                    @php
                                        $availableDays = $doctor->schedules->pluck('day')->map(function($day) {
                                            return strtolower($day);
                                        })->toArray();

                                        $days = [
                                            'sunday' => 'الأحد',
                                            'monday' => 'الإثنين',
                                            'tuesday' => 'الثلاثاء',
                                            'wednesday' => 'الأربعاء',
                                            'thursday' => 'الخميس',
                                            'friday' => 'الجمعة',
                                            'saturday' => 'السبت'
                                        ];
                                    @endphp
                                    @for($i = 0; $i < 14; $i++)
                                        @php
                                            $date = now()->addDays($i);
                                            $isToday = $date->isToday();
                                            $isTomorrow = $date->isTomorrow();
                                            $englishDay = strtolower($date->format('l'));
                                            $arabicDay = $days[$englishDay];
                                            $isAvailable = in_array($englishDay, $availableDays);
                                        @endphp
                                        <div class="date-item" data-date="{{ $date->format('Y-m-d') }}">
                                            <input type="radio"
                                                   name="appointment_date"
                                                   id="date_{{ $date->format('Y-m-d') }}"
                                                   value="{{ $date->format('Y-m-d') }}"
                                                   class="btn-check"
                                                   {{ $selectedDate == $date->format('Y-m-d') ? 'checked' : '' }}
                                                   {{ !$isAvailable ? 'disabled' : '' }}
                                                   required>
                                            <label class="date-card {{ !$isAvailable ? 'unavailable' : '' }}"
                                                   for="date_{{ $date->format('Y-m-d') }}">
                                                <div class="date-weekday">
                                                    @if($isToday)
                                                        اليوم
                                                    @elseif($isTomorrow)
                                                        غداً
                                                    @else
                                                        {{ $arabicDay }}
                                                    @endif
                                                </div>
                                                <div class="date-number">{{ $date->format('d') }}</div>
                                                <div class="date-month">{{ $date->locale('ar')->monthName }}</div>
                                                @if(!$isAvailable)
                                                    <div class="unavailable-badge">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                        غير متاح
                                                    </div>
                                                @else
                                                    <div class="available-badge">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                        متاح
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                @error('appointment_date')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Time Slots -->
                            <div class="time-slots mb-4">
                                <label class="form-label fw-medium d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-clock text-primary"></i>
                                    الحجوزات المتاحة
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2" id="timeSlots">
                                    @forelse($availableSlots as $slot)
                                        <div class="col-lg-3 col-md-4 col-6">
                                            <input type="radio"
                                                   class="btn-check"
                                                   name="appointment_time"
                                                   id="slot_{{ $slot }}"
                                                   value="{{ $slot }}"
                                                   {{ old('appointment_time') == $slot ? 'checked' : '' }}
                                                   required>
                                            <label class="time-slot-card w-100" for="slot_{{ $slot }}">
                                                <i class="bi bi-clock-fill"></i>
                                                {{ $slot }}
                                            </label>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-light border text-center mb-0">
                                                <i class="bi bi-calendar-x text-warning" style="font-size: 2rem"></i>
                                                <p class="mt-2 mb-0">لا توجد مواعيد متاحة في هذا اليوم</p>
                                                <small class="text-muted">يرجى اختيار يوم آخر</small>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                @error('appointment_time')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label fw-medium d-flex align-items-center gap-2">
                                    <i class="bi bi-chat-right-text text-primary"></i>
                                    ملاحظات إضافية
                                    <small class="text-muted">(اختياري)</small>
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes"
                                          name="notes"
                                          rows="3"
                                          placeholder="هل لديك أي معلومات إضافية ترغب في إخبار الطبيب بها؟">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>



                            <div class="d-grid gap-3">
                                <button type="submit" id="submit-btn" class="btn btn-primary btn-lg">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-calendar-check"></i>
                                        <span>تأكيد الحجز</span>
                                    </div>
                                </button>

                                <div id="stripe-payment-button" style="display: none;">
                                    <button type="button" id="stripe-btn" class="btn btn-info btn-lg w-100">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-credit-card-2-front"></i>
                                            <span>الدفع باستخدام Stripe</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>



                    <div class="booking-footer mt-4 pt-4 border-top">
                        <div class="text-center mb-3">
                            <small class="text-muted">الحجز مسبقاً والدخول بأسبقية الحضور</small>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-3 p-3 bg-light rounded-3">
                            <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                            <div class="text-start">
                                <h6 class="mb-1">احجز أونلاين، ادفع في اونلاين!</h6>
                                <small class="text-muted">الدكتور يشترط الحجز المسبق</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>

    .category-badge-0 { background-color: #e8f3ff !important; color: #0d6efd !important; }
    .category-badge-1 { background-color: #e8fff3 !important; color: #198754 !important; }
    .category-badge-2 { background-color: #ffe8e8 !important; color: #dc3545 !important; }
    .category-badge-3 { background-color: #fff8e8 !important; color: #ffc107 !important; }
    .category-badge-4 { background-color: #e8f8ff !important; color: #0dcaf0 !important; }
    .category-badge-5 { background-color: #f3e8ff !important; color: #6f42c1 !important; }

    .badge {
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .badge:hover {
        transform: translateY(-1px);
    }

    .btn-check:checked + .btn-outline-primary {
        background-color: var(--bs-primary);
        color: white;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
    }

    .date-carousel {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding: 8px 4px;
        margin: -8px -4px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .date-carousel::-webkit-scrollbar {
        display: none;
    }

    .date-item {
        min-width: calc(100% / 4 - 9px);
    }

    .date-card {
        background: #FFFFFF;
        border: 1px solid #E8EAED;
        border-radius: 12px;
        padding: 12px 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 4px;
        color: #202124;
    }

    .date-weekday {
        font-size: 0.85rem;
        color: #5F6368;
        opacity: 1;
    }

    .date-number {
        font-size: 1.5rem;
        font-weight: bold;
        line-height: 1;
    }

    .date-month {
        font-size: 0.8rem;
        color: #5F6368;
        opacity: 1;
    }

    .btn-check:checked + .date-card {
        background: #4285F4;
        border-color: #4285F4;
        color: #FFFFFF;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(66, 133, 244, 0.2);
    }

    .btn-check:checked + .date-card .date-weekday,
    .btn-check:checked + .date-card .date-month {
        opacity: 0.9;
        color: rgba(255, 255, 255, 0.95);
    }

    .btn-check:checked + .date-card .available-badge {
        color: rgba(255, 255, 255, 0.95);
    }

    .btn-check:checked + .date-card .available-badge i {
        color: #98ff98;
    }

    .date-card:hover:not(.btn-check:checked + .date-card) {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
    }

    .time-slot-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 500;
    }

    .btn-check:checked + .time-slot-card {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }

    .time-slot-card:hover:not(.btn-check:checked + .time-slot-card) {
        border-color: var(--bs-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .time-slot-card i {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .btn-primary {
        padding: 12px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }

    @media (max-width: 768px) {
        .date-item {
            min-width: calc(100% / 3 - 8px);
        }
    }

    @media (max-width: 576px) {
        .date-item {
            min-width: calc(100% / 2.5 - 8px);
        }
    }

    .date-card.unavailable {
        background: #F8F9FA;
        border-color: #E8EAED;
        opacity: 1;
        cursor: not-allowed;
    }

    .date-card.unavailable:hover {
        transform: none;
        box-shadow: none;
        border-color: #E8EAED;
    }

    .unavailable-badge {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: #D93025;
        margin-top: 0.25rem;
    }

    .unavailable-badge i {
        font-size: 0.875rem;
        color: #EA4335;
    }

    .available-badge {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: #34A853;
        margin-top: 0.25rem;
    }

    .available-badge i {
        font-size: 0.875rem;
        color: #2E9247;
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .schedule-day {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .schedule-day.available {
        background-color: #e8fff3;
        border-color: rgba(25, 135, 84, 0.1);
    }

    .schedule-day.unavailable {
        background-color: #fff5f5;
        border-color: rgba(220, 53, 69, 0.1);
    }

    .day-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .day-name {
        font-weight: 600;
        color: #1e293b;
    }

    .time-slots {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .time-slot {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #475569;
        font-size: 0.875rem;
    }

/* Doctor Profile Styles */
.doctor-cover {
    background-color: #f8fafc;
    padding-top: 100px;
    margin-top: -40px;
}

.cover-image {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 140px;
    background: linear-gradient(45deg, var(--bs-primary), #0dcaf0);
    opacity: 0.1;
}

.doctor-image-container {
    position: relative;
    display: inline-block;
    margin-top: -30px;
}

.doctor-avatar {
    width: 160px;
    height: 160px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
    background-color: #fff;
}

.doctor-avatar:hover {
    transform: scale(1.05);
}

.online-indicator {
    position: absolute;
    bottom: 18px;
    right: 12px;
    width: 16px;
    height: 16px;
    background-color: #22c55e;
    border: 3px solid #fff;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.doctor-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.specialization-badge .badge {
    padding: 8px 16px;
    font-size: 1rem;
    font-weight: 500;
    border-radius: 50px;
}

/* Info Cards */
.info-card {
    padding: 1rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    background: #fff;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.info-card.primary .info-card-icon {
    background-color: #e8f3ff;
    color: #0d6efd;
}

.info-card.success .info-card-icon {
    background-color: #e8fff3;
    color: #198754;
}

.info-card.warning .info-card-icon {
    background-color: #fff8e8;
    color: #ffc107;
}

.info-card-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.5rem;
}

.info-card-content {
    flex: 1;
}

.info-card-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.2;
    color: #1e293b;
}

.info-card-label {
    font-size: 0.875rem;
    color: #64748b;
}

/* Section Styles */
.doctor-section {
    padding: 1rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.description {
    font-size: 1rem;
    line-height: 1.7;
    color: #475569;
}

/* Specialty Tags */
.specialty-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.specialty-tag {
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.specialty-tag:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Rating Section */
.rating-score {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.rating-stars {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.rating-stars i {
    margin: 0 1px;
}

.rating-count {
    font-size: 0.9rem;
}

.rating-bar-item {
    margin-bottom: 0.75rem;
}

.rating-label {
    min-width: 60px;
    font-weight: 500;
}

.rating-progress {
    flex: 1;
}

.rating-progress .progress {
    height: 8px;
    background-color: #f1f5f9;
    border-radius: 4px;
}

.rating-progress .progress-bar {
    border-radius: 4px;
}

.rating-percentage {
    min-width: 50px;
    text-align: right;
    font-size: 0.9rem;
    color: #64748b;
}

/* Category Badge Colors */
.category-badge-0 { background-color: #e8f3ff !important; color: #0d6efd !important; }
.category-badge-1 { background-color: #e8fff3 !important; color: #198754 !important; }
.category-badge-2 { background-color: #ffe8e8 !important; color: #dc3545 !important; }
.category-badge-3 { background-color: #fff8e8 !important; color: #ffc107 !important; }
.category-badge-4 { background-color: #e8f8ff !important; color: #0dcaf0 !important; }
.category-badge-5 { background-color: #f3e8ff !important; color: #6f42c1 !important; }

.location-badge .badge {
    padding: 8px 16px;
    font-size: 0.9rem;
    font-weight: 500;
}

.location-details {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 12px;
}

.location-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e8f3ff;
    border-radius: 8px;
}

.location-icon i {
    font-size: 1.1rem;
}

.location-text {
    flex: 1;
}

.location-text strong {
    display: inline-block;
    margin-left: 0.5rem;
    color: #495057;
}

.location-text span {
    color: #6c757d;
}

@media (max-width: 768px) {
    .doctor-cover {
        padding-top: 80px;
    }

    .doctor-avatar {
        width: 140px;
        height: 140px;
    }

    .doctor-name {
        font-size: 1.5rem;
    }

    .info-card {
        padding: 0.75rem;
    }

    .info-card-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }

    .info-card-value {
        font-size: 1.25rem;
    }

    .info-card-label {
        font-size: 0.8rem;
    }

    .rating-score {
        font-size: 2.5rem;
    }

    .specialty-tag {
        font-size: 0.85rem;
        padding: 6px 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('input[name="appointment_date"]');
    const bookingForm = document.getElementById('bookingForm');
    const appointmentDateInput = bookingForm.querySelector('input[name="appointment_date"]');
    const timeSlotsContainer = document.getElementById('timeSlots');

    // إضافة التعامل مع طرق الدفع
    const paymentMethodInputs = document.querySelectorAll('.payment-method');
    const stripePaymentButton = document.getElementById('stripe-payment-button');
    const submitBtn = document.getElementById('submit-btn');
    const stripeBtn = document.getElementById('stripe-btn');

    // تبديل عرض أزرار الدفع بناءً على طريقة الدفع المختارة
    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value === 'stripe') {
                stripePaymentButton.style.display = 'block';
                submitBtn.textContent = 'متابعة';
            } else {
                stripePaymentButton.style.display = 'none';
                submitBtn.textContent = 'تأكيد الحجز';
            }
        });
    });

    // إضافة إجراء لزر الدفع بواسطة Stripe
    if (stripeBtn) {
        stripeBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // التأكد من اختيار التاريخ والوقت قبل الانتقال للدفع
            const selectedDate = bookingForm.querySelector('input[name="appointment_date"]:checked');
            const selectedTime = bookingForm.querySelector('input[name="appointment_time"]:checked');

            if (!selectedDate || !selectedTime) {
                alert('يرجى اختيار تاريخ ووقت الحجز أولاً');
                return false;
            }

            // إرسال نموذج الحجز إلى الخادم مع توجيه لمعالج Stripe
            bookingForm.action = '{{ route("appointments.payment.create") }}';
            bookingForm.submit();
        });
    }

    // تحويل الوقت إلى معرف صالح
    const sanitizeId = (id) => {
        return id ? id.replace(/[:\s]/g, '_') : '';
    };

    // Add smooth scroll animation with sanitized selector
    const smoothScroll = (target) => {
        if (!target) return;

        if (target.startsWith('#')) {
            target = target.substring(1);
        }
        const element = document.getElementById(sanitizeId(target));
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    };


    // Handle date selection
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            const selectedDate = this.value;
            if (!selectedDate) return;

            appointmentDateInput.value = selectedDate;

            // Show loading state
            timeSlotsContainer.innerHTML = `
                <div class="col-12">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">جاري تحميل الحجوزات المتاحة...</p>
                    </div>
                </div>
            `;

            // Update available time slots
            fetch(`{{ route('appointments.book', $doctor->id) }}?date=${selectedDate}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTimeSlots = doc.getElementById('timeSlots');
                    if (newTimeSlots) {
                        timeSlotsContainer.innerHTML = newTimeSlots.innerHTML;

                        // تحديث معرفات عناصر الوقت
                        const timeSlots = timeSlotsContainer.querySelectorAll('input[type="radio"]');
                        timeSlots.forEach(slot => {
                            if (!slot.id) return;

                            const originalId = slot.id;
                            const newId = sanitizeId(originalId);
                            if (originalId !== newId) {
                                slot.id = newId;
                                const label = timeSlotsContainer.querySelector(`label[for="${originalId}"]`);
                                if (label) {
                                    label.setAttribute('for', newId);
                                }
                            }
                        });

                        smoothScroll('timeSlots');
                    }
                })
                .catch(error => {
                    console.error('Error fetching time slots:', error);
                    timeSlotsContainer.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger text-center mb-0">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                حدث خطأ أثناء تحميل الحجوزات. يرجى المحاولة مرة أخرى.
                            </div>
                        </div>
                    `;
                });
        });
    });
});
</script>
@endpush
@endsection
