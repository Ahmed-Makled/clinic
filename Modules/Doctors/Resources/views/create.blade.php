@extends('layouts.admin')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 mb-4">
                    <h5 class="mb-0 ms-2">إضافة طبيب</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form method="POST" action="{{ route('doctors.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf

                        <!-- المعلومات الأساسية -->
                        <div class="section-divider mb-4">
                            <h6 class="section-title">المعلومات الأساسية</h6>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">الإسم الأول *</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="الإسم الأول"
                                       required />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="lastname">الإسم الأخير *</label>
                                <input type="text"
                                       class="form-control @error('lastname') is-invalid @enderror"
                                       id="lastname"
                                       name="lastname"
                                       value="{{ old('lastname') }}"
                                       placeholder="الإسم الأخير"
                                       required />
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="email">البريد الإلكتروني *</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="البريد الإلكتروني"
                                       required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="password">كلمة المرور *</label>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="password_confirmation">تأكيد كلمة المرور *</label>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="phone">رقم الهاتف *</label>
                                <div class="input-group">
                                    <span class="input-group-text">+20</span>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="ادخل رقم الهاتف"
                                           required />
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label d-block">الجنس *</label>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="ذكر" {{ old('gender') == 'ذكر' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">ذكر</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="انثي" {{ old('gender') == 'انثي' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">انثى</label>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">الحالة</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" {{ old('status') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">نشط</label>
                                </div>
                            </div>
                        </div>

                        <!-- المعلومات المهنية -->
                        <div class="section-divider mb-4 mt-4">
                            <h6 class="section-title">المعلومات المهنية</h6>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="categories">التخصصات *</label>
                                <select class="form-select @error('categories') is-invalid @enderror"
                                        name="categories[]"
                                        id="categories"
                                        multiple
                                        required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="experience_years">سنوات الخبرة *</label>
                                <input type="number"
                                       class="form-control @error('experience_years') is-invalid @enderror"
                                       id="experience_years"
                                       name="experience_years"
                                       value="{{ old('experience_years') }}"
                                       placeholder="عدد سنوات الخبرة"
                                       min="0"
                                       required />
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="price">سعر الكشف (جنيه) *</label>
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price') }}"
                                           placeholder="سعر الكشف"
                                           min="0"
                                           required />
                                    <span class="input-group-text">جنيه</span>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- الصورة الشخصية -->
                        <div class="section-divider mb-4 mt-4">
                            <h6 class="section-title">الصورة الشخصية</h6>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="image">صورة شخصية</label>
                                <input type="file"
                                       class="form-control @error('image') is-invalid @enderror"
                                       id="image"
                                       name="image"
                                       accept="image/*" />
                                <small class="text-muted">يفضل رفع صورة بأبعاد 400×400 بيكسل</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- معلومات العنوان -->
                        <div class="section-divider mb-4 mt-4">
                            <h6 class="section-title">معلومات العنوان</h6>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="address1">العنوان 1</label>
                                <input type="text"
                                       class="form-control @error('address1') is-invalid @enderror"
                                       id="address1"
                                       name="address1"
                                       value="{{ old('address1') }}"
                                       placeholder="العنوان 1" />
                                @error('address1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="address2">العنوان 2</label>
                                <input type="text"
                                       class="form-control @error('address2') is-invalid @enderror"
                                       id="address2"
                                       name="address2"
                                       value="{{ old('address2') }}"
                                       placeholder="العنوان 2" />
                                @error('address2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="city">المدينة</label>
                                <select class="form-select @error('city') is-invalid @enderror"
                                        name="city"
                                        id="city">
                                    <option value="">اختر المدينة</option>
                                    <option value="القاهرة" {{ old('city') == 'القاهرة' ? 'selected' : '' }}>القاهرة</option>
                                    <option value="الإسكندرية" {{ old('city') == 'الإسكندرية' ? 'selected' : '' }}>الإسكندرية</option>
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="district">المنطقة</label>
                                <select class="form-select @error('district') is-invalid @enderror"
                                        name="district"
                                        id="district">
                                    <option value="">اختر المنطقة</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="postal_code">الرمز البريدي</label>
                                <input type="text"
                                       class="form-control @error('postal_code') is-invalid @enderror"
                                       id="postal_code"
                                       name="postal_code"
                                       value="{{ old('postal_code') }}"
                                       placeholder="الرمز البريدي" />
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary ms-1">حفظ</button>
                            <a href="{{ route('doctors.index') }}" class="btn btn-label-secondary">إلغاء</a>
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

    .btn {
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn-primary {
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

    .btn-label-secondary {
        color: #8592a3;
        border: 1px solid #8592a3;
        background: transparent;
    }

    .btn-label-secondary:hover {
        background-color: #8592a3;
        color: #fff;
    }

    .input-group-text {
        background-color: #f5f5f9;
        border-color: #d9dee3;
    }

    .text-muted {
        font-size: 0.875rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0.25rem rgba(67, 94, 190, 0.1);
    }

    .was-validated .form-control:invalid,
    .was-validated .form-select:invalid {
        border-color: #dc3545;
    }

    .was-validated .form-control:valid,
    .was-validated .form-select:valid {
        border-color: #198754;
    }

    .invalid-feedback {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    const form = $('form');

    form.on('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Password match validation
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();

        if (password !== confirmation) {
            event.preventDefault();
            $('#password_confirmation').addClass('is-invalid')
                .siblings('.invalid-feedback')
                .text('كلمة المرور غير متطابقة');
        }

        $(this).addClass('was-validated');
    });

    // Real-time password confirmation validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();

        if (password !== confirmation) {
            $(this).addClass('is-invalid')
                .removeClass('is-valid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">كلمة المرور غير متطابقة</div>');
            }
        } else {
            $(this).removeClass('is-invalid')
                .addClass('is-valid')
                .siblings('.invalid-feedback')
                .remove();
        }
    });
});
</script>
@endpush
@endsection
