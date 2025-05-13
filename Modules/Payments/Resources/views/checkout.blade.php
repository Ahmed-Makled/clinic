@extends('layouts.app')

@section('title', 'صفحة الدفع')

@section('content')
<div class="container mt-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- شريط التقدم -->
            <div class="progress-steps mb-5">
                <div class="progress-step active">
                    <div class="step-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <span class="step-label">الحجز</span>
                </div>
                <div class="progress-step active">
                    <div class="step-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <span class="step-label">الدفع</span>
                </div>
                <div class="progress-step">
                    <div class="step-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <span class="step-label">التأكيد</span>
                </div>
            </div>

            <div class="row">
                <!-- تفاصيل الحجز -->
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="mb-0 py-2"><i class="bi bi-info-circle me-2 text-primary"></i>تفاصيل الحجز</h5>
                        </div>
                        <div class="card-body">
                            <div class="appointment-details">
                                <!-- معلومات الطبيب -->
                                <div class="doctor-info mb-4 pb-4 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="doctor-avatar me-3">
                                            @if($appointment->doctor->image)
                                                <img src="{{ asset('storage/' . $appointment->doctor->image) }}" alt="{{ $appointment->doctor->name }}" class="rounded-circle">
                                            @else
                                                <div class="avatar-placeholder">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $appointment->doctor->name }}</h6>
                                            <p class="text-muted small mb-0">{{ $appointment->doctor->title ?? 'أخصائي' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- تفاصيل الموعد -->
                                <div class="mb-4 pb-3 border-bottom">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="bi bi-calendar3"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">تاريخ الحجز</span>
                                            <span class="detail-value">{{ $appointment->scheduled_at->locale('ar')->translatedFormat('l، d F Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">وقت الحجز</span>
                                            <span class="detail-value">{{ $appointment->scheduled_at->format('h:i A') }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">المريض</span>
                                            <span class="detail-value">{{ $appointment->patient->name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- الرسوم -->
                                <div class="fees-section">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">رسوم الكشف</span>
                                        <span>{{ number_format($appointment->fees) }} ج.م</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">رسوم الخدمة</span>
                                        <span>{{ number_format(0) }} ج.م</span>
                                    </div>

                                    <div class="total-line mt-3 pt-3 border-top">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">الإجمالي</span>
                                            <span class="fw-bold text-primary">{{ number_format($appointment->fees) }} ج.م</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- طرق الدفع -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="mb-0 py-2"><i class="bi bi-credit-card me-2 text-primary"></i>اختر طريقة الدفع</h5>
                        </div>
                        <div class="card-body">
                            <!-- خيارات الدفع -->
                            <div class="payment-options">
                                <!-- بطاقة الائتمان -->
                                <div class="payment-option active" id="card-option">
                                    <div class="payment-option-header">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" checked>
                                            <label class="form-check-label d-flex align-items-center" for="payment_card">
                                                <span>بطاقة الائتمان</span>
                                                <div class="ms-auto">
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d6/Visa_2021.svg" alt="Visa" class="payment-card-icon">
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b7/MasterCard_Logo.svg" alt="MasterCard" class="payment-card-icon">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="payment-option-body">
                                        <p class="text-muted small mb-4">
                                            سيتم تحويلك إلى صفحة الدفع الإلكتروني لإتمام العملية بشكل آمن ومشفر.
                                        </p>
                                        <form action="{{ route('payments.payment.create-session', $appointment) }}" method="POST" id="card-payment-form">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                                <i class="bi bi-credit-card2-front me-2"></i> متابعة للدفع
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات الأمان -->
                            <div class="security-info mt-4 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shield-check text-success me-2"></i>
                                    <span class="text-muted small">جميع البيانات مشفرة ومؤمنة</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- زر العودة -->
                    <div class="text-center mt-4">
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-right me-2"></i> العودة لصفحة الحجز
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* أنماط شريط التقدم */
    .progress-steps {
        display: flex;
        justify-content: center;
        position: relative;
        margin-bottom: 2rem;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #4f46e5;
        z-index: 1;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 2;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
        transition: all 0.3s ease;
        border: 1px solid #4f46e5;
        color: #4f46e5;
    }

    .progress-step.active .step-icon {
        background-color: #4f46e5;
        color: white;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
    }

    .step-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }

    .progress-step.active .step-label {
        color: #4f46e5;
        font-weight: 600;
    }

    /* أنماط تفاصيل الحجز */
    .doctor-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
    }

    .doctor-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e9ecef;
        color: #6c757d;
        font-size: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .detail-icon {
        min-width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: rgba(90, 89, 89, 0.129);
        color: #00;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        display: block;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .detail-value {
        display: block;
        font-weight: 500;
    }

    .fees-section {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
    }

    .total-line {
        color: #2d3748;
    }

    /* أنماط طرق الدفع */
    .payment-option {
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .payment-option.active {
        border-color: #4f46e5;
    }

    .payment-option-header {
        padding: 1rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid transparent;
    }

    .payment-option.active .payment-option-header {
        background-color: #f0f4ff;
    }

    .payment-option-body {
        padding: 1rem;
    }

    .payment-card-icon {
        height: 20px;
        margin-right: 0.5rem;
    }

    .security-info {
        color: #6c757d;
    }


</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // المراقبة فقط على النموذج الوحيد المتوفر (بطاقة الائتمان)
        const cardForm = document.getElementById('card-payment-form');

        if (cardForm) {
            cardForm.addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                if (button) {
                    button.disabled = true;
                    button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> جاري التحويل للدفع...';
                }
            });
        }
    });
</script>
@endpush
@endsection
