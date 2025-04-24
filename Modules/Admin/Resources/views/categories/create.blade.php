@extends('admin::layouts.master')

@section('title', 'إضافة تخصص جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    إضافة تخصص جديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <!-- البيانات الأساسية -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">البيانات الأساسية</h6>
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم التخصص <span class="text-danger">*</span></label>
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
                                    <div class="form-text">وصف مختصر للتخصص يظهر في صفحة التخصصات</div>
                                </div>
                            </div>

                            <!-- الإعدادات المتقدمة -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">الإعدادات المتقدمة</h6>
                                <div class="mb-3">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status"
                                            name="status">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="order" class="form-label">الترتيب</label>
                                    <input type="number"
                                           class="form-control @error('order') is-invalid @enderror"
                                           id="order"
                                           name="order"
                                           value="{{ old('order', 0) }}"
                                           min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">ترتيب ظهور التخصص في القائمة (0 = تلقائي)</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- صورة التخصص -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">صورة التخصص</h6>
                                <div class="image-preview-container mb-3">
                                    <img src="{{ asset('images/placeholder.png') }}"
                                         class="img-thumbnail preview-image"
                                         id="imagePreview"
                                         alt="صورة التخصص">
                                </div>
                                <input type="file"
                                       class="form-control @error('image') is-invalid @enderror"
                                       id="image"
                                       name="image"
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">يفضل رفع صورة مربعة بحجم 300×300 بكسل</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-end">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">إلغاء</a>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.preview-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}
.image-preview-container {
    aspect-ratio: 1;
    overflow: hidden;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // معاينة الصورة
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            }

            reader.readAsDataURL(e.target.files[0]);
        }
    });
});
</script>
@endpush

@endsection