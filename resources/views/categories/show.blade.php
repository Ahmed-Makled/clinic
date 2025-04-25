@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">{{ $category->name }}</h2>

    <div class="row">
        @forelse($doctors as $doctor)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        @if($doctor->image)
                            <a href="{{ route('doctors.show', $doctor->id) }}">
                                <img src="{{ Storage::url($doctor->image) }}" class="card-img-top" alt="{{ $doctor->name }}" style="height: 200px; object-fit: cover;">
                            </a>
                        @else
                            <a href="{{ route('doctors.show', $doctor->id) }}">
                                <img src="{{ asset('images/default-doctor.jpg') }}" class="card-img-top" alt="Doctor" style="height: 200px; object-fit: cover;">
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('doctors.show', $doctor->id) }}" class="text-decoration-none text-dark">
                                {{ $doctor->name }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            {{ $doctor->categories->pluck('name')->implode(' ، ') }}
                        </p>
                        @if($doctor->experience_years)
                            <p class="card-text"><small class="text-muted">خبرة {{ $doctor->experience_years }} سنوات</small></p>
                        @endif
                        <p class="card-text">سعر الكشف: {{ $doctor->price }} جنيه</p>
                        <a href="{{ route('appointments.book', $doctor->id) }}" class="btn btn-primary">احجز موعد</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    لا يوجد أطباء في هذا التخصص حالياً
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
