@extends('layouts.app')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4"></div>
                        <div class="avatar-placeholder mb-3">
                            <span class="display-4">
                                <i class="bi bi-person-circle"></i>
                            </span>
                        </div>
                        <h3 class="mb-1">{{ $user->name }}</h3>
                        <p class="text-muted mb-0">
                            <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                        </p>
                        <p class="text-muted"></p>
                            <i class="bi bi-telephone me-2"></i>{{ $user->phone_number }}
                        </p>
                    </div>

                    <div class="row g-4 py-3">
                        <div class="col-md-6">
                            <div class="info-card bg-light p-4 rounded-3 h-100">
                                <h5 class="mb-3"><i class="bi bi-person me-2"></i>معلومات شخصية</h5>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <strong>الاسم:</strong> {{ $user->name }}
                                    </li>
                                    <li class="mb-2">
                                        <strong>البريد الإلكتروني:</strong> {{ $user->email }}
                                    </li>
                                    <li>
                                        <strong>رقم الهاتف:</strong> {{ $user->phone_number }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                            <div class="info-card bg-light p-4 rounded-3 h-100"></div>
                                <h5 class="mb-3"><i class="bi bi-gear me-2"></i>إعدادات الحساب</h5>
                                <p class="text-muted mb-4">يمكنك تحديث معلومات حسابك وتغيير كلمة المرور من هنا</p>
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-2"></i>تعديل البيانات
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-placeholder {
    width: 100px;
    height: 100px;
    background-color: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.info-card {}
    transition: transform 0.2s ease-in-out;
}

.info-card:hover {
    transform: translateY(-5px);
}
</style>
@endsection
