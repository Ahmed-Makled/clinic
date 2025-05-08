@extends('layouts.app')

@section('title', 'دفع رسوم الحجز')


@section('content')
    <div class="container mt-5 py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <div class="payment-icon-wrapper bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <img src="{{ asset('images/payment/credit-card.svg') }}" alt="بطاقة دفع" height="40"
                                    onerror="this.src='https://img.icons8.com/fluency/96/credit-card-front.png'; this.onerror=null;">
                            </div>
                            <h3 class="fw-bold mb-2">دفع رسوم الحجز</h3>
                            <p class="text-muted mb-0">يرجى مراجعة تفاصيل الحجز قبل الانتقال لصفحة الدفع</p>
                        </div>

                        <div class="checkout-details bg-light p-4 rounded-3 mb-4">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h6 class="text-muted mb-2"><i class="bi bi-person-badge me-2"></i>الطبيب</h6>
                                    <p class="fw-bold mb-0 fs-5">د. {{ $appointment->doctor->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2"><i class="bi bi-person me-2"></i>المريض</h6>
                                    <p class="fw-bold mb-0 fs-5">{{ $appointment->patient->name }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h6 class="text-muted mb-2"><i class="bi bi-calendar me-2"></i>تاريخ الحجز</h6>
                                    <p class="fw-bold mb-0">{{ $appointment->scheduled_at->format('Y-m-d') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2"><i class="bi bi-clock me-2"></i>وقت الحجز</h6>
                                    <p class="fw-bold mb-0">{{ $appointment->scheduled_at->format('h:i A') }}</p>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">المبلغ الإجمالي</h5>
                                <h3 class="mb-0 fw-bold text-primary">{{ number_format($appointment->fees, 2) }} جنيه</h3>
                            </div>
                        </div>

                        <div class="payment-info bg-soft-info p-4 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <div class="payment-info-icon">
                                    <i class="bi bi-info-circle-fill text-info"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">سيتم تحويلك إلى صفحة الدفع الإلكتروني</h6>
                                    <p class="mb-0 text-muted small">جميع المعاملات مشفرة وآمنة بالكامل</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    العودة
                                </a>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('payments.stripe.create-session', $appointment) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                        <span>متابعة للدفع</span>
                                        <i class="bi bi-arrow-left"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="payment-cards-supported text-center mt-4 pt-3 border-top">
                            <p class="text-muted mb-2 small">وسائل الدفع المدعومة</p>
                            <div class="d-flex justify-content-center gap-3">
                                <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" height="30"
                                    onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/d/d6/Visa_2021.svg'; this.onerror=null;">
                                <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" height="30"
                                    onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b7/MasterCard_Logo.svg'; this.onerror=null;">
                                <img src="{{ asset('images/payment/amex.svg') }}" alt="American Express" height="30"
                                    onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg'; this.onerror=null;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .checkout-details {
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .payment-icon-wrapper {
            width: 80px;
            height: 80px;
        }

        .payment-info {
            border: 1px solid rgba(13, 110, 253, 0.1);
        }

        .payment-info-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e8f3ff;
            border-radius: 50%;
        }

        .payment-info-icon i {
            font-size: 1.4rem;
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
        }
    </style>
@endsection
