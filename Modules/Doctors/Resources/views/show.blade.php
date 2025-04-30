@extends('layouts.app')

@section('content')

<div class="doctor-page pt-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-7">
                <div class="border bg-white p-4">
                    <div class="doctor-profile text-center mb-4">
                        @if($doctor->image)
                            <img src="{{ Storage::url($doctor->image) }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/default-doctor.png') }}';"

                                 alt="{{ $doctor->name }}"
                                 class="rounded-circle img-thumbnail mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-doctor.png') }}"
                                 alt="صورة افتراضية"
                                 class="rounded-circle img-thumbnail mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <h4 class="mb-1">{{ $doctor->name }}</h4>
                    </div>
                    <p>{{ $doctor->description }}</p>
                    <p class="">
                        <i class="uil-award"></i>ال {{ $doctor->degree }}
                    </p>
                    <div class="border-top pt-3">
                        التقييم
                        @for ($i = 0; $i < 5; $i++)
                            <i class="uil-star" style="color: {{ $i < $doctor->rating ? '#ffbd45' : '#aaa' }}"></i>
                        @endfor
                    </div>
                    <div class="mt-3">
                        @foreach($doctor->categories as $category)
                            <span class="badge category-badge-{{ $loop->index % 6 }}">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-5 mb-5">
                <div class="border bg-white p-4">
                    <h6 class="text-center mb-3 pb-3 border-bottom">معلومات الحجز</h6>
                    <div class="row">
                        <div class="col-6">
                            <i class="uil-bill" style="font-size: 1rem"></i>
                            الكشف: {{ $doctor->price }} ج م
                        </div>
                        <div class="col-6">
                            <i class="uil-import" style="font-size: 1rem"></i>
                            مدة الإنتظار: {{ $doctor->waiting_time }}دقائق
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <i class="uil-map"></i> {{ $doctor->address }}
                        <h6 class="mt-2">احجز الان وسيتم ارسال تفاصيل العنوان بالكامل ورقم العيادة</h6>
                    </div>

                    <h5 class="text-center border-bottom border-top mt-3 py-4">اخـتــــــــار ميعاد الــحــجــز</h5>

                    <div class="dates row mt-4 justify-content-between">
                        <div class="col-12">
                            @if($appointments->isEmpty())
                                <div class="text-center">
                                    <i class="uil-calendar-alt" style="font-size: 3rem; color: #dc3545;"></i>
                                    <h5 class="mt-3">عذراً، لا توجد حجوزات متاحة حالياً</h5>
                                    <p>يرجى المحاولة في وقت لاحق</p>
                                </div>
                            @else
                                <div class="owl-carousel">
                                    @foreach($appointments as $appointment)
                                        <div>
                                            <ul class="border list-unstyled text-center mb-0 pr-0">
                                                <li class="bg-primary text-center text-white p-1">
                                                    @php
                                                        $dayName = Carbon\Carbon::parse($appointment->day->name_en)->format('l');
                                                    @endphp
                                                    @if($dayName === now()->format('l'))
                                                        اليوم
                                                    @elseif($dayName === now()->addDay()->format('l'))
                                                        غداً
                                                    @else
                                                        {{ config('app.days.' . $dayName) }}
                                                    @endif
                                                </li>
                                                @foreach(json_decode($appointment->period) as $time)
                                                    <li class="my-3 font-weight-bold px-1 small py-2 text-primary">{{ $time }}</li>
                                                @endforeach
                                                @if($appointment->is_unavailable)
                                                    <li class="mt-5 font-weight-bold">لا يوجد حجوزات متاحة</li>
                                                @endif
                                            </ul>
                                            <a href="{{ route('appointments.reserve', ['doctor' => $doctor->id]) }}" class="btn btn-success text-white btn-sm mt-0 btn-block">إحجز</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <p class="text-center mt-4 border-bottom pb-3">الحجز مسبقا و الدخول بأسبقية الحضور</p>

                    <div class="text-center d-flex justify-content-center">
                        <i class="uil-medkit mt-n2" style="font-size: 2.5rem; color: rgb(124, 187, 104);"></i>
                        <div class="info">
                            <h6>احجز أونلاين، ادفع في العيادة!</h6>
                            الدكتور يشترط الحجز المسبق!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
