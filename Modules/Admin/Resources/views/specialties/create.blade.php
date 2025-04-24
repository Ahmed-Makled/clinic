@extends('admin::layouts.master')

@section('title', 'إضافة تخصص جديد')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 mb-4">
                    <h5 class="mb-0 ms-2">إضافة تخصص جديد</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form action="{{ route('admin.specialties.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
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
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary ms-1">حفظ</button>
                            <a href="{{ route('admin.specialties.index') }}" class="btn btn-label-secondary">إلغاء</a>
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

    .mb-3 {
        margin-bottom: 1.5rem !important;
    }

    .image-preview-container {
        text-align: center;
        margin-bottom: 1rem;
    }

    .preview-image {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview
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
