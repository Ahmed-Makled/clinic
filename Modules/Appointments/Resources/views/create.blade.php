@extends('layouts.admin')

@section('title', 'إضافة موعد جديد')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">إضافة موعد جديد</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('appointments.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label for="doctor_id" class="form-label">الطبيب *</label>
                    <select class="form-select @error('doctor_id') is-invalid @enderror"
                            id="doctor_id"
                            name="doctor_id"
                            required>
                        <option value="">اختر الطبيب</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="patient_id" class="form-label">المريض *</label>
                    <select class="form-select @error('patient_id') is-invalid @enderror"
                            id="patient_id"
                            name="patient_id"
                            required>
                        <option value="">اختر المريض</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
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

                <div class="col-md-6">
                    <label for="status" class="form-label">الحالة *</label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status"
                            name="status"
                            required>
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="fees" class="form-label">رسوم الكشف</label>
                    <div class="input-group">
                        <input type="number"
                               class="form-control @error('fees') is-invalid @enderror"
                               id="fees"
                               name="fees"
                               value="{{ old('fees') }}"
                               min="0"
                               step="0.01">
                        <span class="input-group-text">جنيه</span>
                    </div>
                    @error('fees')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_paid"
                                       name="is_paid"
                                       value="1"
                                       {{ old('is_paid') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_paid">تم الدفع</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_important"
                                       name="is_important"
                                       value="1"
                                       {{ old('is_important') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_important">موعد مهم</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror"
                              id="notes"
                              name="notes"
                              rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">حفظ</button>
                    <a href="{{ route('appointments.index') }}" class="btn btn-label-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript initialization here
});
</script>
@endpush
