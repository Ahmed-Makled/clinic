@extends('layouts.app')

@section('content')
<div class="container mt-5 py-5">
    <div class="row">
        <!-- Doctor Info Card -->
        <div class="col-md-4">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-3">معلومات الطبيب</h5>
                    <div class="text-center mb-3">
                        @if($doctor->image)
                            <img src="{{ Storage::url($doctor->image) }}"
                                 alt="{{ $doctor->name }}"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-doctor.png') }}';"
                                 class="rounded-circle shadow-sm"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="stat-icon bg-primary-subtle text-primary">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        @endif
                        <h6 class="text-center mb-3 mt-3">{{ $doctor->name }}</h6>
                        <p class="text-muted">{{ $doctor->categories->pluck('name')->implode(' ، ') }}</p>
                    </div>
                    <div class="border-top pt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="stat-icon bg-success-subtle text-success" style="width: 32px; height: 32px; font-size: 1rem;">
                                <i class="bi bi-cash"></i>
                            </div>
                            <div class="ms-2">
                                <div class="text-muted small">سعر الكشف</div>
                                <strong>{{ $doctor->consultation_fee }} جنيه</strong>
                            </div>
                        </div>
                        @if($doctor->experience_years)
                        <div class="d-flex align-items-center mb-2">
                            <div class="stat-icon bg-info-subtle text-info" style="width: 32px; height: 32px; font-size: 1rem;">
                                <i class="bi bi-award"></i>
                            </div>
                            <div class="ms-2">
                                <div class="text-muted small">سنوات الخبرة</div>
                                <strong>{{ $doctor->experience_years }} سنوات</strong>
                            </div>
                        </div>
                        @endif
                        @if($doctor->address)
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-warning-subtle text-warning" style="width: 32px; height: 32px; font-size: 1rem;">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="ms-2">
                                <div class="text-muted small">العنوان</div>
                                <strong>{{ $doctor->address }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4 pb-3 border-bottom">حجز موعد جديد</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('appointments.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="appointment_date" class="form-label">تاريخ الموعد <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date"
                                           class="form-control @error('appointment_date') is-invalid @enderror"
                                           id="appointment_date"
                                           name="appointment_date"
                                           value="{{ old('appointment_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="appointment_time" class="form-label">وقت الموعد <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <select class="form-select @error('appointment_time') is-invalid @enderror"
                                            id="appointment_time"
                                            name="appointment_time"
                                            required>
                                        <option value="">اختر الوقت</option>
                                        <!-- Time slots will be populated dynamically -->
                                    </select>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="3"
                                      placeholder="أضف أي ملاحظات خاصة بالموعد...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary position-relative">
                                <span class="btn-text">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    تأكيد الحجز
                                </span>
                                <span class="loading-spinner d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    جاري الحجز...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const loadingSpinner = submitBtn.querySelector('.loading-spinner');

    // Handle form submission
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            // Show loading state
            btnText.classList.add('d-none');
            loadingSpinner.classList.remove('d-none');
            submitBtn.disabled = true;
        }
        form.classList.add('was-validated');
    });

    // Handle date change
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');

    dateInput.addEventListener('change', function() {
        // Add loading state to time select
        timeSelect.disabled = true;
        timeSelect.innerHTML = '<option value="">جاري تحميل الحجوزات المتاحة...</option>';

        // Fetch available time slots
        fetch(`/appointments/available-slots/${document.querySelector('[name="doctor_id"]').value}/${this.value}`)
            .then(response => response.json())
            .then(data => {
                timeSelect.innerHTML = '<option value="">اختر الوقت</option>';
                data.slots.forEach(slot => {
                    const option = new Option(slot.formatted_time, slot.value);
                    timeSelect.add(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                timeSelect.innerHTML = '<option value="">حدث خطأ في تحميل الحجوزات</option>';
            })
            .finally(() => {
                timeSelect.disabled = false;
            });
    });
});
</script>
@endpush

@endsection
