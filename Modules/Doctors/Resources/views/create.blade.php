@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">إضافة طبيب جديد</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">رقم الهاتف</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_ids" class="form-label">التخصصات</label>
                            <select class="form-select @error('category_ids') is-invalid @enderror"
                                    id="category_ids" name="category_ids[]" multiple required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">نبذة عن الطبيب</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                      id="bio" name="bio" rows="3">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">الصورة الشخصية</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="governorate" class="form-label">المحافظة</label>
                            <input type="text" class="form-control" id="governorate"
                                   name="governorate" value="{{ old('governorate') }}">
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">المدينة</label>
                            <input type="text" class="form-control" id="city"
                                   name="city" value="{{ old('city') }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <input type="text" class="form-control" id="address"
                                   name="address" value="{{ old('address') }}">
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">سعر الكشف</label>
                            <input type="number" class="form-control" id="price"
                                   name="price" value="{{ old('price') }}" step="0.01">
                        </div>

                        <div class="mb-3">
                            <label for="waiting_time" class="form-label">وقت الانتظار (بالدقائق)</label>
                            <input type="number" class="form-control" id="waiting_time"
                                   name="waiting_time" value="{{ old('waiting_time') }}">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                            <a href="{{ route('doctors.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#category_ids').select2({
        theme: 'bootstrap-5',
        placeholder: 'اختر التخصصات',
        allowClear: true
    });
});
</script>
@endpush
@endsection
