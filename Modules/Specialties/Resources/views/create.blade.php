@extends('layouts.admin')

@section('title', 'إضافة تخصص جديد')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 mb-4">
                    <h5 class="mb-0 ms-2">إضافة تخصص جديد</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form action="{{ route('specialties.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم التخصص *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">الوصف</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="parent_id" class="form-label">التخصص الرئيسي</label>
                                    <select name="parent_id" id="parent_id" class="form-select" data-icon="bi-diagram-3" data-color="#0d6efd">
                                        <option value="">اختر التخصص الرئيسي</option>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty->id }}" data-icon="bi-folder">{{ $specialty->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">نوع التخصص</label>
                                    <select name="type" id="type" class="form-select" data-icon="bi-tag" data-color="#198754" required>
                                        <option value="general" data-icon="bi-stars">عام</option>
                                        <option value="specialized" data-icon="bi-bookmark-star">متخصص</option>
                                        <option value="subspecialty" data-icon="bi-diagram-2">تخصص فرعي</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select name="status" id="status" class="form-select" data-icon="bi-toggle2-on" data-color="#0dcaf0" required>
                                        <option value="active" data-icon="bi-check-circle">نشط</option>
                                        <option value="inactive" data-icon="bi-x-circle">غير نشط</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary ms-1">حفظ</button>
                            <a href="{{ route('specialties.index') }}" class="btn btn-label-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
