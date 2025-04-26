@extends('layouts.app')

@section('content')
<div class="container mt-5 py-5">
    <div class="row">
        <!-- Doctor Info Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">معلومات الطبيب</h5>
                    <div class="text-center mb-3">
                        @if($doctor->image)
                            <img src="{{ Storage::url($doctor->image) }}" alt="{{ $doctor->name }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/default-doctor.png') }}';"

                            class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-doctor.jpg') }}" alt="Doctor" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                    </div>
                    <h6 class="text-center mb-3">{{ $doctor->name }}</h6>
                    <p class="text-muted text-center">{{ $doctor->categories->pluck('name')->implode(' ، ') }}</p>
                    <hr>
                    <p class="mb-2"><strong>سعر الكشف:</strong> {{ $doctor->price }} جنيه</p>
                    @if($doctor->experience_years)
                        <p class="mb-2"><strong>سنوات الخبرة:</strong> {{ $doctor->experience_years }} سنوات</p>
                    @endif
                    @if($doctor->address)
                        <p class="mb-2"><strong>العنوان:</strong> {{ $doctor->address }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">حجز موعد</h5>
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        <div class="form-group mb-3">
                            <label for="date" class="form-label">اختر التاريخ</label>
                            <select name="date" id="date" class="form-select" data-icon="bi-calendar3" data-color="#0d6efd" required>
                                <option value="">اختر التاريخ</option>
                                @foreach($availableDates as $date)
                                    <option value="{{ $date }}" data-icon="bi-calendar-date">
                                        {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="time" class="form-label">اختر الوقت</label>
                            <select name="time" id="time" class="form-select" data-icon="bi-clock" data-color="#198754" required disabled>
                                <option value="">اختر الوقت</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">تاريخ الموعد</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date"
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_time" class="form-label">وقت الموعد</label>
                            <select class="form-select" id="appointment_time" name="appointment_time" required>
                                <option value="">اختر الوقت</option>
                                @foreach($timeSlots as $slot)
                                    <option value="{{ $slot }}">{{ $slot }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">تأكيد الحجز</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
