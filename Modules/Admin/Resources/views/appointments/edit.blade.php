@extends('admin::layouts.master')

@section('title', 'تعديل الموعد')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="patient_id" class="form-label">المريض</label>
                        <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">اختر المريض</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}"
                                        {{ (old('patient_id', $appointment->patient_id) == $patient->id) ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="doctor_id" class="form-label">الطبيب</label>
                        <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                            <option value="">اختر الطبيب</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                        data-fees="{{ $doctor->price }}"
                                        {{ (old('doctor_id', $appointment->doctor_id) == $doctor->id) ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="scheduled_at" class="form-label">موعد الحجز</label>
                        <input type="datetime-local"
                               class="form-control @error('scheduled_at') is-invalid @enderror"
                               id="scheduled_at"
                               name="scheduled_at"
                               value="{{ old('scheduled_at', $appointment->scheduled_at->format('Y-m-d\TH:i')) }}"
                               required>
                        @error('scheduled_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="fees" class="form-label">الرسوم</label>
                        <div class="input-group">
                            <input type="number"
                                   class="form-control @error('fees') is-invalid @enderror"
                                   id="fees"
                                   name="fees"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('fees', $appointment->fees) }}">
                            <span class="input-group-text">ج.م</span>
                        </div>
                        @error('fees')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="is_paid"
                                   name="is_paid"
                                   value="1"
                                   {{ old('is_paid', $appointment->is_paid) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_paid">تم الدفع</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="is_important"
                                   name="is_important"
                                   value="1"
                                   {{ old('is_important', $appointment->is_important) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_important">موعد مهم</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>
                                قيد الانتظار
                            </option>
                            <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>
                                مكتمل
                            </option>
                            <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                                ملغي
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes"
                                  name="notes"
                                  rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const doctorSelect = document.getElementById('doctor_id');
    const feesInput = document.getElementById('fees');

    doctorSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const defaultFees = selectedOption.dataset.fees;
        if (defaultFees && !feesInput.value) {
            feesInput.value = defaultFees;
        }
    });
});
</script>
@endpush

@endsection
