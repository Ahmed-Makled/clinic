@extends('layouts.app')

@section('content')
<header class="header position-relative vh-100">
    <div class="overlay position-absolute w-100 h-100">
        <div class="container mt-5 pt-5 text-white">
            <div class="row justify-content-center">
                <div class="col-9">
                    <h1 class="font-weight-bold my-4">اسهل طريقة لحجز احسن واكبر <span class="text-primary">دكاترة</span> في مصر</h1>
                    <p>احجز أونلاين أو كلم 77777</p>
                </div>
            </div>

            <div class="buttons-group text-center my-5">
                <a class="btn btn-primary btn-lg rounded-pill px-4 mx-1" href="{{ route('search') }}">إحجز الآن</a>
                <a class="btn btn-primary btn-lg rounded-pill px-4 mx-1" href="{{ route('contact') }}">اتصل بنا</a>
            </div>

            <div class="tabs shadow bg-white text-dark">
                <div class="d-flex">
                    <div class="w-50 pt-4">
                        <div class="line text-center font-weight-bold pb-4" data-tab-name="call">
                            <span>
                                مكالمة دكتور <i class="fas fa-phone"></i><br>
                                كشف عبر مكاملة مع الدكتور
                            </span>
                        </div>
                    </div>
                    <div class="w-50 pt-4">
                        <div class="line active text-center font-weight-bold pb-4" data-tab-name="reserve">
                            <span>
                                احجز دكتور <i class="far fa-calendar-plus"></i> <br>
                                الفحص او الاجراء
                            </span>
                        </div>
                    </div>
                </div>

                <div class="tabs-container">
                    <div class="p-4 text-center" style="display: none" id="call">
                        مكالمة مع دكتور <i class="uil-phone"></i>
                        <button class="btn btn-primary btn-lg rounded-pill">Call <i class="uil-phone"></i></button>
                    </div>

                    <div class="p-4" id="reserve">
                        <form id="search" action="{{ route('search') }}" method="GET">
                            <div class="d-flex justify-content-between align-items-center">
                                <div style="border: 1px solid #e6e6e6" class="p-3 mx-2">
                                    <label class="mb-3 text-muted">أنا أبحث عن طبيب</label>
                                    <select name="category" id="category" class="custom-select border">
                                        <option value="">إختر التخصص</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="border: 1px solid #e6e6e6" class="p-3 mx-2">
                                    <label class="mb-3 text-muted">فى محافظة</label>
                                    <select name="governorate" id="governorate" class="custom-select border">
                                        <option value="all">كل المحافظات</option>
                                        @foreach(config('governorates') as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="border: 1px solid #e6e6e6" class="p-3 mx-2">
                                    <label class="mb-3 text-muted">فى منطقة</label>
                                    <select name="area" id="area" disabled class="custom-select border">
                                        <option>كل المناطق</option>
                                    </select>
                                </div>
                                <div style="border: 1px solid #e6e6e6" class="p-3 mx-2">
                                    <label class="mb-3 text-muted">الأطباء</label>
                                    <select name="doctors" id="doctors" disabled class="custom-select border">
                                        <option value="">إختر الطبيب</option>
                                    </select>
                                </div>
                                <div class="border p-3 mx-2">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        بحث <i class="uil-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="mySlide" class="carousel slide overflow-hidden h-100" data-ride="carousel">
        <div class="carousel-item h-100 active">
            <img src="{{ asset('images/slide3.jpg') }}" class="d-block w-100 h-100" />
        </div>
        <div class="carousel-item h-100">
            <img src="{{ asset('images/slide1.jpg') }}" class="d-block w-100 h-100" />
        </div>
        <div class="carousel-item h-100">
            <img src="{{ asset('images/slide2.jpg') }}" class="d-block w-100 h-100" />
        </div>
    </div>
</header>

<section class="about py-5 text-center">
    <div class="container">
        <h2>إزاى تحجز معانا.</h2>
        <div class="row mt-5">
            <div class="col-4 text-center">
                <i class="uil-user" style="font-size: 1.75rem"></i>
                <h4 class="mt-3">إبحث على دكتور</h4>
                <p>بالتخصص و المنطقة و التامين و سعر الكشف</p>
            </div>
            <div class="col-4 text-center">
                <i class="uil-user" style="font-size: 1.75rem"></i>
                <h4 class="mt-3">قارن واختار</h4>
                <p>بالتخصص و المنطقة و التامين و سعر الكشف</p>
            </div>
            <div class="col-4 text-center">
                <i class="uil-book" style="font-size: 1.75rem"></i>
                <h4 class="mt-3">احجز موعدك</h4>
                <p>بالتخصص و المنطقة و التامين و سعر الكشف</p>
            </div>
        </div>
    </div>
</section>
@endsection
