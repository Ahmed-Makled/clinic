@extends('layouts.app')

@section('title', 'صفحة الدفع')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="mb-3">
                            <i class="bi bi-credit-card text-primary display-1"></i>
                        </div>
                        <h2 class="h3">الدفع باستخدام بطاقة الائتمان</h2>
                        <p class="text-muted">يرجى إكمال عملية الدفع للتأكيد النهائي للحجز</p>
                    </div>

                    <div class="appointment-summary mb-5">
                        <h5 class="border-bottom pb-2 mb-4">تفاصيل الحجز</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3 rounded-circle bg-light p-2">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">الطبيب</small>
                                        <div class="fw-medium">{{ $appointment->doctor->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3 rounded-circle bg-light p-2">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">تاريخ الحجز</small>
                                        <div class="fw-medium">{{ $appointment->formatted_date }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3 rounded-circle bg-light p-2">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">الوقت</small>
                                        <div class="fw-medium">{{ $appointment->scheduled_at->format('h:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3 rounded-circle bg-light p-2">
                                        <i class="bi bi-cash"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">رسوم الكشف</small>
                                        <div class="fw-bold text-primary">{{ number_format($appointment->fees) }} ج.م</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button id="checkout-button" class="btn btn-primary btn-lg">
                            <i class="bi bi-credit-card me-2"></i> الدفع الآن ({{ number_format($appointment->fees) }} ج.م)
                        </button>
                        <p class="mt-3 small text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            جميع المعاملات مشفرة وآمنة
                        </p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-link">
                    <i class="bi bi-arrow-left me-1"></i> العودة لصفحة الحجز
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const stripe = Stripe('{{ config('stripe.key') }}');
    const checkoutButton = document.getElementById('checkout-button');

    checkoutButton.addEventListener('click', function() {
      stripe.redirectToCheckout({
        sessionId: '{{ $checkout_session->id }}'
      }).then(function(result) {
        if (result.error) {
          alert('خطأ: ' + result.error.message);
        }
      });
    });
  });
</script>
@endpush