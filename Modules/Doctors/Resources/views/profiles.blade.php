@extends('layouts.app')

@section('content')


    <div class="container">
        @if (session('message'))
            <div class="alert alert-info">
                {{ session('message') }}
            </div>
        @endif

        <h1 class="h2 text-right mb-4">الأطباء</h1>

        <!-- فلاتر البحث -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('doctors.profiles') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="category" class="form-label">التخصص</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">جميع التخصصات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="governorate" class="form-label">المحافظة</label>
                        <select name="governorate" id="governorate" class="form-select">
                            <option value="">جميع المحافظات</option>
                            @foreach($governorates as $governorate)
                                <option value="{{ $governorate->id }}" {{ request('governorate') == $governorate->id ? 'selected' : '' }}>
                                    {{ $governorate->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="experience" class="form-label">سنوات الخبرة</label>
                        <select name="experience" id="experience" class="form-select">
                            <option value="">الكل</option>
                            <option value="0-2" {{ request('experience') == '0-2' ? 'selected' : '' }}>أقل من سنتين</option>
                            <option value="2-5" {{ request('experience') == '2-5' ? 'selected' : '' }}>2-5 سنوات</option>
                            <option value="5-10" {{ request('experience') == '5-10' ? 'selected' : '' }}>5-10 سنوات</option>
                            <option value="10-+" {{ request('experience') == '10-+' ? 'selected' : '' }}>أكثر من 10 سنوات</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="fee_range" class="form-label">سعر الكشف</label>
                        <select name="fee_range" id="fee_range" class="form-select">
                            <option value="">الكل</option>
                            <option value="0-100" {{ request('fee_range') == '0-100' ? 'selected' : '' }}>حتى 100 جنيه</option>
                            <option value="100-200" {{ request('fee_range') == '100-200' ? 'selected' : '' }}>100-200 جنيه</option>
                            <option value="200-300" {{ request('fee_range') == '200-300' ? 'selected' : '' }}>200-300 جنيه</option>
                            <option value="300-+" {{ request('fee_range') == '300-+' ? 'selected' : '' }}>أكثر من 300 جنيه</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> بحث
                        </button>
                        <a href="{{ route('doctors.profiles') }}" class="btn btn-outline-secondary">
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @if ($doctors->count())
                @foreach ($doctors as $doctor)
                    <div class="col-lg-3 col-md-4 col-sm-2 col-12 mt-4">
                        <div class="border bg-white p-4 position-relative overflow-hidden">
                            <div class="badge font-weight-bold position-absolute bg-primary text-white text-center text-uppercase">
                                {{ $doctor->categories->first() ? $doctor->categories->first()->name : 'طبيب عام' }}
                            </div>

                            <div class="text-center border-bottom mt-4 mb-3 pb-3">
                                @if($doctor->image)
                                    <img src="{{ Storage::url($doctor->image)}}"
                                    onerror="this.onerror=null; this.src='{{ asset('images/default-doctor.png') }}';"

                                    class="rounded-pill img-thumbnail"
                                        width="100" alt="{{ $doctor->name }}">
                                @else
                                    <img src="{{ asset('images/default-doctor.png') }}" class="rounded-pill img-thumbnail"
                                        width="100" alt="صورة افتراضية">
                                @endif
                                <div class="mt-3">
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="uil-star" style="color: {{ $i < ($doctor->rating ?? 0) ? '#ffbd45' : '#aaa' }}"></i>
                                    @endfor
                                </div>
                            </div>

                            <ul class="list-unstyled pr-0">
                                <li class="list-inline-item mr-0">
                                    <i class="uil-award-alt"></i> {{ $doctor->experience_years }} سنوات خبرة
                                </li>
                                <li class="list-inline-item mr-2">
                                    <i class="uil-bill"></i> {{ $doctor->consultation_fee }} <small>ج م</small>
                                </li>
                            </ul>

                            <h5 class="mb-3">{{ $doctor->name }}</h5>

                            <div class="mb-3">
                                <p class="text-muted mb-1">
                                    <i class="uil-location-point"></i>
                                    {{ $doctor->governorate->name ?? '' }} - {{ $doctor->city->name ?? '' }}
                                </p>
                                <p>{{ Str::limit($doctor->description ?? '', 150) }}</p>
                            </div>

                            @foreach($doctor->categories as $category)
                                <span class="badge category-badge-{{ $category->id % 6 }}">{{ $category->name }}</span>
                            @endforeach

                            <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-primary rounded-pill px-3">
                                إقرأ المزيد <i class="uil-angle-left"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

                <div class="col-12 mt-4">
                    {{ $doctors->links() }}
                </div>
            @else
                <div class="col-12 text-center">
                    <i class="uil-frown" style="font-size: 1.5rem"></i>
                    <h5>لم يتم العثور على أى طبيب</h5>
                    <p>لا يوجد أطباء مطابقين لمعايير البحث. يرجى تعديل معايير البحث والمحاولة مرة أخرى.</p>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        .card {
            border-radius: 10px;
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
    </style>
    @endpush
@endsection
