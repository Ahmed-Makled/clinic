@extends('layouts.admin')

@section('header_icon')
<i class="bi bi-person-badge text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('doctors.index') }}" class="text-decoration-none">الأطباء</a>
</li>
<li class="breadcrumb-item active">تعديل طبيب</li>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-4">
            <div class="card-header border-bottom py-3 mb-4">
                <h5 class="mb-0 ms-2">تعديل بيانات الطبيب</h5>
            </div>
            <div class="card-body px-4 py-3">
                <form method="POST" action="{{ route('doctors.update', $doctor) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    @if(request()->has('redirect_to'))
                        <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                    @endif

                    <!-- المعلومات الأساسية -->
                    <div class="section-divider mb-4">
                        <h6 class="section-title text-primary fw-bold">المعلومات الأساسية</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name">الاسم *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $doctor->name) }}" placeholder="اسم الطبيب"  />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email">البريد الإلكتروني *</label>
                            <input type="email" style="direction: rtl;"
                                class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                value="{{ old('email', $doctor->email) }}" placeholder="البريد الإلكتروني"  />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="phone">رقم الهاتف *</label>
                            <div class="input-group">
                                <span class="input-group-text">+20</span>
                                <input type="text" class="form-control" id="phone"
                                    name="phone" value="{{ old('phone', $doctor->phone) }}" placeholder="ادخل رقم الهاتف"  />
                            </div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label d-block">الجنس *</label>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="male" value="ذكر"
                                    {{ old('gender', $doctor->gender) == 'ذكر' ? 'checked' : '' }} >
                                <label class="form-check-label" for="male">ذكر</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="female" value="انثي"
                                    {{ old('gender', $doctor->gender) == 'انثي' ? 'checked' : '' }}>
                                <label class="form-check-label" for="female">أنثى</label>
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label">الحالة *</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="status" id="status" value="1"
                                    {{ old('status', $doctor->status) == true ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">نشط</label>
                            </div>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- المعلومات المهنية -->
                    <div class="section-divider mb-4 mt-4">
                        <h6 class="section-title text-primary fw-bold">المعلومات المهنية</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="title">المسمى الوظيفي *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title', $doctor->title) }}"
                                placeholder="مثال: استشاري، أخصائي، طبيب" >
                            <small class="text-muted">المسمى الوظيفي الرسمي للطبيب مثل (استشاري - أخصائي - طبيب)</small>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="degree">الدرجة العلمية</label>
                            <input type="text" class="form-control @error('degree') is-invalid @enderror"
                                id="degree" name="degree" value="{{ old('degree', $doctor->degree) }}"
                                placeholder="مثال: دكتوراه، ماجستير، بكالوريوس طب">
                            <small class="text-muted">المؤهل العلمي للطبيب مثل (دكتوراه - ماجستير - بكالوريوس)</small>
                            @error('degree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">التخصص *</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror"
                                data-icon="bi-briefcase-medical" data-color="#0d6efd" required>
                                <option value="">اختر التخصص</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-icon="bi-heart-pulse"
                                        {{ old('category_id', $doctor->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">اختر تخصصاً واحداً للطبيب مثل (طب أطفال - طب أسنان - جراحة)</small>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="experience_years">سنوات الخبرة *</label>
                            <input type="number" style="direction: rtl"
                                class="form-control @error('experience_years') is-invalid @enderror"
                                id="experience_years" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}"
                                placeholder="عدد سنوات الخبرة" min="0"  />
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="description">نبذة عن الطبيب</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="4"
                                placeholder="اكتب نبذة تعريفية عن الطبيب تشمل خبراته ومؤهلاته">{{ old('description', $doctor->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- معلومات الكشف والحجوزات -->
                    <div class="section-divider mb-4 mt-4">
                        <h6 class="section-title text-primary fw-bold">معلومات الكشف والحجوزات</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="consultation_fee">سعر الكشف (جنيه) *</label>
                            <div class="input-group">
                                <input type="number" style="direction: rtl"
                                    class="form-control @error('consultation_fee') is-invalid @enderror"
                                    id="consultation_fee" name="consultation_fee" value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                                    placeholder="سعر الكشف" min="0"  />
                                <span class="input-group-text">جنيه</span>
                            </div>
                            @error('consultation_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="waiting_time">مدة الانتظار (بالدقائق) *</label>
                            <div class="input-group">
                                <input type="number" class="form-control  @error('waiting_time') is-invalid @enderror"
                                    name="waiting_time" id="waiting_time"
                                    value="{{ old('waiting_time', $doctor->waiting_time) }}"
                                    min="0" />
                                <span class="input-group-text">دقيقة</span>
                            </div>
                            @error('waiting_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>اليوم</th>
                                            <th>متاح</th>
                                            <th>من الساعة</th>
                                            <th>إلى الساعة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['sunday' => 'الأحد', 'monday' => 'الإثنين', 'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة', 'saturday' => 'السبت'] as $dayKey => $dayName)
                                            @php
                                                $schedule = $doctor->schedules->where('day', $dayKey)->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $dayName }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input schedule-availability" type="checkbox"
                                                            name="schedules[{{ $loop->index }}][is_available]"
                                                            id="day_{{ $loop->index }}_available"
                                                            value="1"
                                                            {{ $schedule && $schedule->is_active ? 'checked' : '' }}>
                                                        <input type="hidden" name="schedules[{{ $loop->index }}][day]" value="{{ $dayKey }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control schedule-time @error("schedules.{$loop->index}.start_time") is-invalid @enderror"
                                                        name="schedules[{{ $loop->index }}][start_time]"
                                                        id="day_{{ $loop->index }}_start"
                                                        value="{{ old("schedules.{$loop->index}.start_time", $schedule ? $schedule->start_time->format('H:i') : '') }}"
                                                        {{ $schedule && $schedule->is_active ? '' : 'disabled' }}>
                                                    @error("schedules.{$loop->index}.start_time")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control schedule-time @error("schedules.{$loop->index}.end_time") is-invalid @enderror"
                                                        name="schedules[{{ $loop->index }}][end_time]"
                                                        id="day_{{ $loop->index }}_end"
                                                        value="{{ old("schedules.{$loop->index}.end_time", $schedule ? $schedule->end_time->format('H:i') : '') }}"
                                                        {{ $schedule && $schedule->is_active ? '' : 'disabled' }}>
                                                    @error("schedules.{$loop->index}.end_time")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات العنوان -->
                    <div class="section-divider mb-4 mt-4">
                        <h6 class="section-title text-primary fw-bold">معلومات العنوان</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="governorate_id" class="form-label">المحافظة *</label>
                            <select name="governorate_id" id="governorate_id" class="form-select @error('governorate_id') is-invalid @enderror " data-icon="bi-geo-alt"
                                data-color="#198754" >
                                <option value="">اختر المحافظة</option>
                                @foreach($governorates as $governorate)
                                    <option value="{{ $governorate->id }}" data-icon="bi-pin-map"
                                        {{ old('governorate_id', $doctor->governorate_id) == $governorate->id ? 'selected' : '' }}>
                                        {{ $governorate->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('governorate_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="city_id">المدينة *</label>
                            <select class="form-select @error('city_id') is-invalid @enderror" name="city_id" id="city_id" >
                                <option value="">اختر المدينة</option>
                            </select>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="address">عنوان العيادة *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" rows="2"
                                placeholder="العنوان التفصيلي للعيادة" >{{ old('address', $doctor->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- الصورة الشخصية -->
                    <div class="section-divider mb-4 mt-4">
                        <h6 class="section-title text-primary fw-bold">الصورة الشخصية</h6>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if($doctor->image)
                                <div class="current-image mb-3">
                                    <img src="{{ Storage::url($doctor->image) }}"
                                        onerror="this.src='{{ asset('images/default-doctor.png') }}'"
                                        alt="الصورة الحالية" class="mb-2" style="max-width: 200px; border-radius: 8px;">
                                    <div class="text-muted">الصورة الحالية</div>
                                </div>
                            @endif

                            <div class="image-upload-zone" id="dropZone">
                                <input type="file" class="d-none @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" />

                                <div class="upload-placeholder" style="cursor: pointer;">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    <h6 class="mb-2">اسحب الصورة الجديدة هنا أو انقر للاختيار</h6>
                                    <small class="text-muted d-block">يفضل رفع صورة بأبعاد 400×400 بيكسل</small>
                                    <small class="text-muted d-block">الصيغ المدعومة: JPG, JPEG, PNG, WEBP</small>
                                </div>

                                <div class="image-preview d-none" id="imagePreview">
                                    <img src="#" alt="معاينة الصورة" style="max-width: 200px; border-radius: 8px;">
                                    <span class="remove-image" title="حذف الصورة">&times;</span>
                                </div>

                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary ms-1">حفظ التغييرات</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-label-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.image-upload-zone {
    border: 2px dashed rgba(var(--bs-primary-rgb), 0.2);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.02) 0%, rgba(37, 99, 235, 0.02) 100%);
    position: relative;
    cursor: pointer;
}

.image-upload-zone.dragover {
    border-color: var(--bs-primary);
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.05) 0%, rgba(37, 99, 235, 0.05) 100%);
    transform: scale(1.02);
}

.current-image {
    text-align: center;
    margin-bottom: 1.5rem;
}

.current-image img {
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 20px;
    border: 3px solid rgba(var(--bs-primary-rgb), 0.1);
    box-shadow:
        0 8px 16px rgba(var(--bs-primary-rgb), 0.1),
        0 2px 4px rgba(var(--bs-primary-rgb), 0.05);
    transition: all 0.3s ease;
}

.current-image:hover img {
    transform: translateY(-5px) rotate(2deg);
    box-shadow:
        0 12px 20px rgba(var(--bs-primary-rgb), 0.15),
        0 4px 8px rgba(var(--bs-primary-rgb), 0.1);
    border-color: rgba(var(--bs-primary-rgb), 0.2);
}

.current-image .text-muted {
    margin-top: 0.75rem;
    font-size: 0.875rem;
    color: #64748b !important;
}

.upload-placeholder {
    padding: 2rem;
}

.upload-icon {
    font-size: 2.5rem;
    color: var(--bs-primary);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.image-upload-zone:hover .upload-icon {
    transform: translateY(-5px);
}

.upload-placeholder h6 {
    color: #1e293b;
    margin-bottom: 0.75rem;
}

.upload-placeholder small {
    color: #64748b;
}

.image-preview {
    position: relative;
    display: inline-block;
}

.image-preview img {
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 20px;
    border: 3px solid rgba(var(--bs-primary-rgb), 0.1);
    box-shadow:
        0 8px 16px rgba(var(--bs-primary-rgb), 0.1),
        0 2px 4px rgba(var(--bs-primary-rgb), 0.05);
}

.remove-image {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1rem;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    border: 2px solid white;
    transition: all 0.2s ease;
}

.remove-image:hover {
    transform: scale(1.1);
    background: #c82333;
}

</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    const dropZone = $('#dropZone');
    const imageInput = $('#image');
    const imagePreview = $('#imagePreview');
    const previewImg = imagePreview.find('img');
    const uploadPlaceholder = $('.upload-placeholder');
// Toggle time inputs based on schedule availability
    // Toggle time inputs based on schedule availability
    $('.schedule-availability').on('change', function() {
        const row = $(this).closest('tr');
        const timeInputs = row.find('.schedule-time');

        if ($(this).is(':checked')) {
            timeInputs.prop('disabled', false);
        } else {
            timeInputs.prop('disabled', true);
            // Resetear los valores de tiempo a vacío cuando se deselecciona un día
            timeInputs.val(null);
        }
    });

    // Handle drag and drop
    dropZone.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    dropZone.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    dropZone.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const file = e.originalEvent.dataTransfer.files[0];
        handleImageFile(file);
    });

    // Handle click on upload placeholder
    uploadPlaceholder.on('click', function(e) {
        e.stopPropagation();
        imageInput.click();
    });

    // Handle file input change
    imageInput.on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleImageFile(file);
        }
    });

    // Handle image preview
    function handleImageFile(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.attr('src', e.target.result);
                imagePreview.removeClass('d-none');
                uploadPlaceholder.addClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    // Handle remove image
    $('.remove-image').on('click', function() {
        imageInput.val('');
        imagePreview.addClass('d-none');
        uploadPlaceholder.removeClass('d-none');
        previewImg.attr('src', '#');
    });

    // Form validation
    const form = $('form');
    form.on('submit', function (event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // إضافة كود التعامل مع المحافظات والمدن
    $('#governorate_id').on('change', function() {
        const governorateId = $(this).val();
        const cities = @json($governorates->pluck('cities', 'id'));
        const citySelect = $('#city_id');

        citySelect.empty().append('<option value="">اختر المدينة</option>');

        if (governorateId && cities[governorateId]) {
            cities[governorateId].forEach(function(city) {
                citySelect.append(new Option(city.name, city.id));
            });
        }
    });

    // تحميل المدن عند تحميل الصفحة إذا كانت هناك محافظة محددة
    const oldGovernorate = $('#governorate_id').val();
    if (oldGovernorate) {
        $('#governorate_id').trigger('change');

        // اختيار المدينة المحفوظة إن وجدت
        const oldCity = "{{ old('city_id', $doctor->city_id) }}";
        if (oldCity) {
            $('#city_id').val(oldCity);
        }
    }
});
</script>
@endpush

@endsection
