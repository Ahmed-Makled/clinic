@extends('layouts.admin')

@section('title', 'تفاصيل الموعد')

@section('actions')
    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary">
        <i class="bi bi-pencil"></i> تعديل الموعد
    </a>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xl-8">
            <!-- تفاصيل الموعد -->
            <div class="card mb-4">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">تفاصيل الموعد</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رقم الموعد</label>
                            <p class="lead">#{{ $appointment->id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الحالة</label>
                            <p>
                                <span class="badge bg-{{ $appointment->status_color }}">
                                    {{ $appointment->status_text }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">التاريخ</label>
                            <p class="lead">{{ $appointment->scheduled_at->format('Y-m-d') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">الوقت</label>
                            <p class="lead">{{ $appointment->scheduled_at->format('h:i A') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">رسوم الكشف</label>
                            <p class="lead">
                                {{ number_format($appointment->fees, 2) }} جنيه
                                @if($appointment->is_paid)
                                    <span class="badge bg-success ms-2">تم الدفع</span>
                                @else
                                    <span class="badge bg-warning ms-2">لم يتم الدفع</span>
                                @endif
                            </p>
                        </div>
                        @if($appointment->is_important)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">الأهمية</label>
                                <p><span class="badge bg-danger">موعد مهم</span></p>
                            </div>
                        @endif
                    </div>

                    @if($appointment->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <label class="form-label text-muted">ملاحظات</label>
                                <p class="lead">{{ $appointment->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- معلومات الطبيب -->
            <div class="card mb-4">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">معلومات الطبيب</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        @if($appointment->doctor->image)
                            <img src="{{ asset('storage/' . $appointment->doctor->image) }}"
                                 class="rounded me-3"
                                 width="100"
                                 alt="{{ $appointment->doctor->name }}">
                        @else
                            <div class="avatar-initial rounded me-3 bg-label-primary p-3">
                                <i class="bi bi-person-badge fs-3"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $appointment->doctor->name }}</h5>
                            <div class="mb-1">
                                @foreach($appointment->doctor->categories as $category)
                                    <span class="badge bg-primary">{{ $category->name }}</span>
                                @endforeach
                            </div>
                            <p class="text-muted mb-0">
                                <i class="bi bi-envelope me-1"></i> {{ $appointment->doctor->email }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="bi bi-telephone me-1"></i> {{ $appointment->doctor->phone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات المريض -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">معلومات المريض</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="avatar-initial rounded me-3 bg-label-info p-3">
                            <i class="bi bi-person fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $appointment->patient->name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-envelope me-1"></i> {{ $appointment->patient->user->email }}
                            </p>
                            @if($appointment->patient->phone)
                                <p class="text-muted mb-0">
                                    <i class="bi bi-telephone me-1"></i> {{ $appointment->patient->phone }}
                                </p>
                            @endif
                            @if($appointment->patient->gender)
                                <p class="text-muted mb-0">
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
