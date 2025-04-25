@extends('layouts.admin')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 mb-4">
                    <h5 class="mb-0 ms-2">تعديل بيانات الطبيب</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form method="POST" action="{{ route('doctors.update', $doctor) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- المعلومات الأساسية -->
                        <div class="section-divider mb-4">
                            <h6 class="section-title">المعلومات الأساسية</h6>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name">الإسم الأول *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                       value="{{ old('name', $doctor->name) }}" required />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="lastname">الإسم الأخير *</label>
                                <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname"
                                       value="{{ old('lastname', $doctor->lastname) }}" required />
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="email">البريد الإلكتروني *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                       value="{{ old('email', $doctor->email) }}" required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="phone">رقم الهاتف *</label>
                                <div class="input-group">
                                    <span class="input-group-text">+20</span>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                           value="{{ old('phone', $doctor->phone) }}" required />
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label d-block">الجنس *</label>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="ذكر"
                                           {{ $doctor->gender == 'ذكر' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">ذكر</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="انثي"
                                           {{ $doctor->gender == 'انثي' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">انثى</label>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">الحالة</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="status" id="status"
                                           {{ $doctor->status ? 'checked' : '' }}>
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
                                <label class="form-label" for="categories">التخصص *</label>
                                <select class="form-select @error('categories') is-invalid @enderror" name="categories[]" id="categories" required multiple>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ in_array($category->id, old('categories', $doctor->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
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
                                <input type="number" class="form-control @error('experience_years') is-invalid @enderror"
                                       id="experience_years" name="experience_years"
                                       value="{{ old('experience_years', $doctor->experience_years) }}"
                                       min="0" required />
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="consultation_fee">سعر الكشف (جنيه) *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('consultation_fee') is-invalid @enderror"
                                           id="consultation_fee" name="consultation_fee"
                                           value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                                           min="0" required />
                                    <span class="input-group-text">جنيه</span>
                                </div>
                                @error('consultation_fee')
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
                                @if($doctor->image)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $doctor->image) }}" alt="صورة الطبيب" class="rounded" style="max-width: 200px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" />
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
                                <input type="text" class="form-control" id="address1" name="address1"
                                       value="{{ old('address1', $doctor->address1) }}" />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="address2">العنوان 2</label>
                                <input type="text" class="form-control" id="address2" name="address2"
                                       value="{{ old('address2', $doctor->address2) }}" />
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="city">المدينة</label>
                                <select class="form-select" name="city" id="city">
                                    <option value="">اختر المدينة</option>
                                    <option value="القاهرة" {{ $doctor->city == 'القاهرة' ? 'selected' : '' }}>القاهرة</option>
                                    <option value="الإسكندرية" {{ $doctor->city == 'الإسكندرية' ? 'selected' : '' }}>الإسكندرية</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="district">المنطقة</label>
                                <select class="form-select" name="district" id="district">
                                    <option value="">اختر المنطقة</option>
                                    <!-- سيتم تحميل المناطق بناءً على المدينة المختارة -->
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="postal_code">الرمز البريدي</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code"
                                       value="{{ old('postal_code', $doctor->postal_code) }}" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="categories" class="form-label">التخصصات</label>
                                <select name="categories[]" id="categories" class="form-select" data-icon="bi-briefcase-medical" data-color="#0d6efd" multiple required>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-icon="bi-heart-pulse" {{ in_array($category->id, $doctor->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="governorate" class="form-label">المحافظة</label>
                                <select name="governorate" id="governorate" class="form-select" data-icon="bi-geo-alt" data-color="#198754" required>
                                    <option value="">اختر المحافظة</option>
                                    @foreach(config('governorates') as $key => $value)
                                    <option value="{{ $key }}" data-icon="bi-pin-map" {{ $doctor->governorate == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="area" class="form-label">المنطقة</label>
                                <select name="area" id="area" class="form-select" data-icon="bi-geo" data-color="#6c757d" required>
                                    <option value="">اختر المنطقة</option>
                                    <option value="{{ $doctor->area }}" selected>{{ $doctor->area }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">الحالة</label>
                                <select name="status" id="status" class="form-select" data-icon="bi-toggle2-on" data-color="#0dcaf0" required>
                                    <option value="active" data-icon="bi-check-circle" {{ $doctor->status == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" data-icon="bi-x-circle" {{ $doctor->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary ms-1">حفظ التغييرات</button>
                            <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-label-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
