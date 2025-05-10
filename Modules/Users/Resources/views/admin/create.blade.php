@extends('layouts.admin')

@section('header_icon')
<i class="bi bi-people text-primary me-2 fs-5"></i>
@endsection ر

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('users.index') }}" class="text-decoration-none">المستخدمين</a>
</li>
<li class="breadcrumb-item active">إضافة مستخدم</li>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-4">
            <div class="card-header border-bottom py-3 mb-4">
                <h5 class="mb-0 ms-2">إضافة مستخدم جديد</h5>
            </div>
            <div class="card-body px-4 py-3">
                <form method="POST" action="{{ route('users.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="name">الاسم *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="email">البريد الإلكتروني *</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="phone">رقم الهاتف *</label>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="role">الدور *</label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    name="role"
                                    id="role"
                                    required>
                                <option value="">اختر الدور</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="password">كلمة المرور *</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="password_confirmation">تأكيد كلمة المرور *</label>
                            <input type="password"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input @error('status') is-invalid @enderror"
                                       id="status"
                                       name="status"
                                       value="1"
                                       {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">نشط</label>
                            </div>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary ms-1">حفظ</button>
                        <a href="{{ route('users.index') }}" class="btn btn-label-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });
});
</script>
@endpush
@endsection
