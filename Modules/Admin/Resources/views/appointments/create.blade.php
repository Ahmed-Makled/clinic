@extends('admin::layouts.master')

@section('title', 'إضافة موعد جديد')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.appointments.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="patient_id" class="form-label">المريض</label>
                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
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

            <div class="mb-3">
                <label for="doctor_id" class="form-label">الطبيب</label>
                <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
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

            <div class="mb-3">
                <label for="scheduled_at" class="form-label">موعد الحجز</label>
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

            <div class="mb-3">
                <label for="status" class="form-label">الحالة</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
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
                          rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
