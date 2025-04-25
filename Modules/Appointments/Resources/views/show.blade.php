@extends('layouts.admin')

@section('title', 'تفاصيل الموعد')

@section('actions')
    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary">
        <i class="bi bi-pencil me-2"></i> تعديل الموعد
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8">
            <!-- تفاصيل الموعد -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-bold">تفاصيل الموعد</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">رقم الموعد</label>
                            <p class="h5 fw-semibold mb-0">#{{ $appointment->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">الحالة</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $appointment->status_color }} bg-opacity-10 text-{{ $appointment->status_color }} px-3 py-2">
                                    {{ $appointment->status_text }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">التاريخ</label>
                            <p class="h5 fw-semibold mb-0">{{ $appointment->scheduled_at->format('Y-m-d') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">الوقت</label>
                            <p class="h5 fw-semibold mb-0">{{ $appointment->scheduled_at->format('h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">رسوم الكشف</label>
                            <p class="h5 fw-semibold mb-0">
                                {{ number_format($appointment->fees, 2) }} جنيه
                                @if($appointment->is_paid)
                                    <span class="badge bg-success-subtle text-success ms-2">تم الدفع</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning ms-2">لم يتم الدفع</span>
                                @endif
                            </p>
                        </div>
                        @if($appointment->is_important)
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">الأهمية</label>
                                <p class="mb-0">
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>موعد مهم
                                    </span>
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($appointment->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label text-muted small mb-1">ملاحظات</label>
                                <p class="mb-0 lh-base">{{ $appointment->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- معلومات الطبيب -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-bold">معلومات الطبيب</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        @if($appointment->doctor->image)
                            <img src="{{ asset('storage/' . $appointment->doctor->image) }}"
                                 class="rounded me-3"
                                 width="80"
                                 height="80"
                                 style="object-fit: cover;"
                                 alt="{{ $appointment->doctor->name }}">
                        @else
                            <div class="rounded me-3 bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-person-badge fs-3 text-primary"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 class="fw-semibold mb-2">{{ $appointment->doctor->name }}</h5>
                            <div class="mb-2">
                                @foreach($appointment->doctor->categories as $category)
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $category->name }}</span>
                                @endforeach
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-envelope me-1"></i> {{ $appointment->doctor->email }}
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-telephone me-1"></i> {{ $appointment->doctor->phone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات المريض -->
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-bold">معلومات المريض</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="rounded me-3 bg-info bg-opacity-10 p-3">
                            <i class="bi bi-person fs-3 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-semibold mb-2">{{ $appointment->patient->name }}</h5>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-envelope me-1"></i> {{ $appointment->patient->user->email }}
                            </p>
                            @if($appointment->patient->phone)
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-telephone me-1"></i> {{ $appointment->patient->phone }}
                                </p>
                            @endif
                            @if($appointment->patient->gender)
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-gender-ambiguous me-1"></i> {{ $appointment->patient->gender }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
