@extends('layouts.app')

@section('title', 'تم إلغاء الدفع')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <div class="canceled-icon mb-4">
                        <div class="p-3 bg-light-warning rounded-circle d-inline-flex mb-3">
                            <i class="bi bi-exclamation-circle text-warning display-1"></i>
                        </div>
                        <h2 class="mb-3">تم إلغاء عملية الدفع</h2>
                        <p class="text-muted mb-4">لم تكتمل عملية الدفع، ولكن حجزك لا يزال محفوظًا</p>
                    </div>

                    <div class="appointment-info bg-light p-4 rounded mb-4">
                        <h5 class="border-bottom pb-2 mb-3">تفاصيل الحجز</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="mb-1"><strong>الطبيب:</strong></p>
                                <p class="mb-0">{{ $appointment->doctor->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1"><strong>التاريخ:</strong></p>
                                <p class="mb-0">{{ $appointment->formatted_date }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1"><strong>الوقت:</strong></p>
                                <p class="mb-0">{{ $appointment->scheduled_at->format('h:i A') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1"><strong>رسوم الكشف:</strong></p>
                                <p class="mb-0 text-danger fw-bold">{{ number_format($appointment->fees) }} ج.م (لم يتم الدفع)</p>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="{{ route('payments.checkout', $appointment) }}" class="btn btn-primary">
                            <i class="bi bi-credit-card me-2"></i> المحاولة مرة أخرى
                        </a>
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-calendar-check me-2"></i> العودة للحجز
                        </a>
                    </div>

                    <div class="mt-4">
                        <p class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            يمكنك إتمام الدفع في وقت لاحق ولكن تأكد من استكمال الدفع قبل موعد الحجز
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }
</style>
@endpush