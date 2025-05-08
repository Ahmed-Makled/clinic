@extends('layouts.app')

@section('title', 'تم الدفع بنجاح')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <div class="success-icon mb-4">
                        <div class="p-3 bg-light-success rounded-circle d-inline-flex mb-3">
                            <i class="bi bi-check-circle-fill text-success display-1"></i>
                        </div>
                        <h2 class="mb-3">تم الدفع بنجاح!</h2>
                        <p class="text-muted mb-4">تم تأكيد الدفع وتم تحديث حالة حجزك</p>
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
                                <p class="mb-0 text-success fw-bold">{{ number_format($appointment->fees) }} ج.م (تم الدفع)</p>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-primary">
                            <i class="bi bi-calendar-check me-2"></i> عرض تفاصيل الحجز
                        </a>
                        @if(auth()->user()->hasRole('Patient'))
                            <a href="{{ route('profile') }}" class="btn btn-outline-secondary ms-2">
                                <i class="bi bi-person me-2"></i> الصفحة الشخصية
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-light-success {
        background-color: rgba(57, 218, 138, 0.1);
    }
</style>
@endpush
