@extends('admin::layouts.master')

@section('title', 'التخصصات')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة تخصص
    </a>
@endsection

@section('content')
<div class="row g-4">
    @forelse($categories as $category)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $category->name }}</h5>
                            <p class="text-muted mb-0">
                                <small>
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $category->created_at->format('Y-m-d') }}
                                </small>
                            </p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="dropdown-item">
                                        <i class="bi bi-pencil me-2"></i>
                                        تعديل
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('admin.categories.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا التخصص؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i>
                                            حذف
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if($category->description)
                        <p class="card-text mb-3">{{ $category->description }}</p>
                    @endif

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2 rounded bg-light text-center">
                                <div class="fw-bold text-primary mb-1">{{ $category->doctors_count }}</div>
                                <small class="text-muted">الأطباء</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded bg-light text-center">
                                <div class="fw-bold text-success mb-1">{{ $category->appointments_count }}</div>
                                <small class="text-muted">المواعيد</small>
                            </div>
                        </div>
                    </div>

                    @if($category->doctors_count > 0)
                        <div class="mt-3">
                            <h6 class="fw-bold mb-2">الأطباء</h6>
                            <div class="avatar-stack">
                                @foreach($category->doctors->take(3) as $doctor)
                                    <div class="avatar-stack-item"
                                         data-bs-toggle="tooltip"
                                         title="{{ $doctor->name }}">
                                        @if($doctor->image)
                                            <img src="{{ asset('storage/' . $doctor->image) }}"
                                                 alt="{{ $doctor->name }}"
                                                 class="rounded-circle">
                                        @else
                                            <div class="avatar-placeholder">
                                                {{ strtoupper(substr($doctor->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @if($category->doctors_count > 3)
                                    <div class="avatar-stack-item more">
                                        <span>+{{ $category->doctors_count - 3 }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <img src="{{ asset('images/empty-categories.svg') }}"
                         alt="لا توجد تخصصات"
                         class="empty-state-image mb-4"
                         style="max-width: 200px">
                    <h4 class="text-muted mb-3">لا توجد تخصصات</h4>
                    <p class="text-muted mb-3">لم يتم إضافة أي تخصصات بعد. قم بإضافة تخصص جديد للبدء.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>
                        إضافة تخصص
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($categories->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
@endif

@push('styles')
<style>
.avatar-stack {
    display: flex;
    align-items: center;
}
.avatar-stack-item {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: -8px;
    border: 2px solid #fff;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.avatar-stack-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.avatar-stack-item.more {
    background-color: #6c757d;
    color: #fff;
    font-weight: bold;
}
.avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #6c757d;
    color: #fff;
    font-size: 12px;
    font-weight: bold;
}
.empty-state-image {
    opacity: 0.5;
}
</style>
@endpush

@endsection
