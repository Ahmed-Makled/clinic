@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize me-3 text-end">إكمال بيانات الطبيب</h6>
                            <a href="{{ route('users.index') }}" class="btn bg-gradient-dark btn-sm mb-0 ms-3">العودة للمستخدمين</a>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <form method="post" action="{{ route('doctors.storeFromUser') }}" enctype="multipart/form-data" dir="rtl">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="row">
                                <div class="card card-plain border-radius-lg my-3">
                                    <div class="card-header bg-light pb-0 text-center">
                                        <h5>معلومات المستخدم الحالية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="name">الاسم</label>
                                                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="email">البريد الإلكتروني</label>
                                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="phone">رقم الهاتف</label>
                                                    <input type="tel" class="form-control" value="{{ $user->phone_number }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- معلومات أساسية -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="consultation_fee">سعر الكشف <span class="text-danger">*</span></label>
                                        <input type="number" name="consultation_fee" id="consultation_fee" step="0.01" class="form-control @error('consultation_fee') is-invalid @enderror" required>
                                        @error('consultation_fee')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="waiting_time">مدة الانتظار (بالدقائق)</label>
                                        <input type="number" name="waiting_time" id="waiting_time" class="form-control @error('waiting_time') is-invalid @enderror">
                                        @error('waiting_time')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- التخصص -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="categories">التخصص <span class="text-danger">*</span></label>
                                        <select name="categories[]" id="categories" class="form-control @error('categories') is-invalid @enderror" multiple required>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('categories')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="gender">الجنس <span class="text-danger">*</span></label>
                                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                            <option value="">اختر الجنس</option>
                                            <option value="ذكر">ذكر</option>
                                            <option value="انثي">أنثى</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="experience_years">سنوات الخبرة</label>
                                        <input type="number" name="experience_years" id="experience_years" class="form-control @error('experience_years') is-invalid @enderror">
                                        @error('experience_years')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الموقع -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="governorate_id">المحافظة <span class="text-danger">*</span></label>
                                        <select name="governorate_id" id="governorate_id" class="form-control @error('governorate_id') is-invalid @enderror" required>
                                            <option value="">اختر المحافظة</option>
                                            @foreach($governorates as $governorate)
                                                <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('governorate_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="city_id">المدينة <span class="text-danger">*</span></label>
                                        <select name="city_id" id="city_id" class="form-control @error('city_id') is-invalid @enderror" required>
                                            <option value="">اختر المدينة</option>
                                        </select>
                                        @error('city_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="address">العنوان</label>
                                        <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror">
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- صورة الطبيب -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="image">صورة الطبيب</label>
                                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                                        @error('image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- السيرة الذاتية والوصف -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="bio">السيرة الذاتية</label>
                                        <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror"></textarea>
                                        @error('bio')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="description">الوصف</label>
                                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror"></textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- أزرار التحكم -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn bg-gradient-success btn-lg">حفظ بيانات الطبيب</button>
                                    <a href="{{ route('users.index') }}" class="btn bg-gradient-danger btn-lg">إلغاء</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تحميل المدن عند تغيير المحافظة
        const governorateSelect = document.getElementById('governorate_id');
        const citySelect = document.getElementById('city_id');

        // بيانات المحافظات والمدن
        const governorates = @json($governorates);

        governorateSelect.addEventListener('change', function() {
            const governorateId = this.value;
            citySelect.innerHTML = '<option value="">اختر المدينة</option>';

            if (governorateId) {
                const selectedGovernorate = governorates.find(g => g.id == governorateId);
                if (selectedGovernorate && selectedGovernorate.cities) {
                    selectedGovernorate.cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                }
            }
        });
    });
</script>
@endpush
