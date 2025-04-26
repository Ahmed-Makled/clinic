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
                    <p class="mb-2"><strong>سعر الكشف:</strong> {{ $doctor->consultation_fee }} جنيه</p>
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

                        <div class="form-group mb-3">
                            <label for="appointment_date" class="form-label">تاريخ الموعد <span class="text-danger">*</span></label>
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

                        <div class="form-group mb-3">
                            <label for="appointment_time" class="form-label">وقت الموعد <span class="text-danger">*</span></label>
                            <select class="form-select @error('appointment_time') is-invalid @enderror"
                                    id="appointment_time"
                                    name="appointment_time"
                                    required>
                                <option value="">اختر الوقت</option>
                                @foreach($timeSlots as $slot)
                                    <option value="{{ $slot }}" {{ old('appointment_time') == $slot ? 'selected' : '' }}>
                                        {{ $slot }}
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-calendar-check me-1"></i> تأكيد الحجز
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
    // Enable form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endpush

@endsection
