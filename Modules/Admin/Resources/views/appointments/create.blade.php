@extends('admin::layouts.master')

@section('title', 'إضافة موعد جديد')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 mb-4">
                    <h5 class="mb-0 ms-2">إضافة موعد جديد</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form method="POST" action="{{ route('admin.appointments.store') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="doctor_id" class="form-label">الطبيب *</label>
                                    <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                        <option value="">اختر الطبيب</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                    data-fees="{{ $doctor->price }}"
                                                    {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">المريض *</label>
                                    <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                        <option value="">اختر المريض</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}"
                                                    {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                {{ $patient->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="scheduled_at" class="form-label">موعد الحجز *</label>
                                    <input type="datetime-local"
                                           class="form-control @error('scheduled_at') is-invalid @enderror"
                                           id="scheduled_at"
                                           name="scheduled_at"
                                           value="{{ old('scheduled_at') }}"
                                           required>
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fees" class="form-label">قيمة الكشف *</label>
                                    <input type="number"
                                           class="form-control @error('fees') is-invalid @enderror"
                                           id="fees"
                                           name="fees"
                                           value="{{ old('fees') }}"
                                           step="0.01"
                                           required>
                                    @error('fees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_paid" name="is_paid" value="1" {{ old('is_paid') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_paid">تم الدفع</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_important" name="is_important" value="1" {{ old('is_important') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_important">موعد مهم</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes"
                                              name="notes"
                                              rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary ms-1">حفظ</button>
                            <a href="{{ route('admin.appointments.index') }}" class="btn btn-label-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
    .card {
        background: #fff;
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #566a7f;
    }

    .form-control,
    .form-select {
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #d9dee3;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0.25rem rgba(67, 94, 190, 0.1);
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn {}
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn-primary {}
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #364b98;
        border-color: #364b98;
        transform: translateY(-1px);
        box-shadow: 0 0.125rem 0.25rem rgba(67, 94, 190, 0.3);
    }

    .btn-label-secondary {}
        color: #8592a3;
        border: 1px solid #8592a3;
        background: transparent;
    }

    .btn-label-secondary:hover {
        background-color: #8592a3;
        color: #fff;
    }

    .mb-3 {
        margin-bottom: 1.5rem !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill fees based on selected doctor
    $('#doctor_id').on('change', function() {}
        const selectedOption = $(this).find('option:selected');
        const fees = selectedOption.data('fees');
        if (fees) {}
            $('#fees').val(fees);
        }
    });

    // Form validation
    const form = $('form');

    form.on('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        $(this).addClass('was-validated');
    });

    // Set minimum date for scheduled_at
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    $('#scheduled_at').attr('min', minDateTime);
});
</script>
@endpush
@endsection
