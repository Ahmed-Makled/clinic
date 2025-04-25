@extends('layouts.app')

@section('content')
        <div class="container">
            @if (session('message'))
                <div class="alert alert-info">
                    {{ session('message') }}
                </div>
            @endif

            <h1 class="h2 text-right mb-4">الأطباء</h1>
            <div class="row">
                {{-- Static data for demonstration --}}
                @php
                    $doctors = [
                        [
                            'id' => 1,
                            'name' => 'د. أحمد محمد',
                            'category' => ['name' => 'طب عام'],
                            'avatar' => '/1.png',
                            'rating' => 4,
                            'degree' => 'دكتوراه',
                            'price' => '200',
                            'description' => 'طبيب متخصص في الطب العام مع خبرة تزيد عن 15 عاماً في مجال الرعاية الصحية الأولية والطب الوقائي.'
                        ],
                        [
                            'id' => 2,
                            'name' => 'د. سارة خالد',
                            'category' => ['name' => 'أطفال'],
                            'avatar' => '/2.png',
                            'rating' => 5,
                            'degree' => 'استشاري',
                            'price' => '250',
                            'description' => 'استشارية طب الأطفال متخصصة في رعاية الأطفال حديثي الولادة والرعاية الصحية للأطفال.'
                        ],
                        [
                            'id' => 3,
                            'name' => 'د. محمود عبدالله',
                            'category' => ['name' => 'باطنة'],
                            'avatar' => '/3.png',
                            'rating' => 3,
                            'degree' => 'أخصائي',
                            'price' => '180',
                            'description' => 'أخصائي الأمراض الباطنية مع خبرة في تشخيص وعلاج أمراض الجهاز الهضمي والقلب.'
                        ],
                        [
                            'id' => 4,
                            'name' => 'د. ليلى حسن',
                            'category' => ['name' => 'نساء وولادة'],
                            'avatar' => '/4.png',
                            'rating' => 4,
                            'degree' => 'استشارية',
                            'price' => '300',
                            'description' => 'استشارية نساء وولادة مع خبرة في رعاية النساء الحوامل والعمليات القيصرية.'
                        ],
                        [
                            'id' => 5,
                            'name' => 'د. ياسر علي',
                            'category' => ['name' => 'جراحة'],
                            'avatar' => '/5.png',
                            'rating' => 4,
                            'degree' => 'استشاري',
                            'price' => '400',
                            'description' => 'استشاري جراحة عامة مع خبرة في العمليات الجراحية المعقدة.'
                        ],
                        [
                            'id' => 6,
                            'name' => 'د. فاطمة الزهراء',
                            'category' => ['name' => 'جلدية'],
                            'avatar' => '/5.png',
                            'rating' => 5,
                            'degree' => 'استشارية',
                            'price' => '350',
                            'description' => 'استشارية جلدية مع خبرة في علاج الأمراض الجلدية والتجميلية.'
                        ],

                    ];
                @endphp

                @if (count($doctors))
                    @foreach ($doctors as $doctor)
                        <div class="col-lg-3 col-md-4 col-sm-2 col-12 mt-4">
                            <div class="border bg-white p-4 position-relative overflow-hidden">
                                <div
                                    class="badge font-weight-bold position-absolute bg-primary text-white text-center text-uppercase">
                                    {{ $doctor['category']['name'] }}
                                </div>

                                <div class="text-center border-bottom mt-4 mb-3 pb-3">
                                    <img src="{{ asset('images/doctors' . $doctor['avatar']) }}" class="rounded-pill img-thumbnail"
                                        width="100" alt="">
                                    <div class="mt-3">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="uil-star" style="color: {{ $i < $doctor['rating'] ? '#ffbd45' : '#aaa' }}"></i>
                                        @endfor
                                    </div>
                                </div>

                                <ul class="list-unstyled pr-0">
                                    <li class="list-inline-item mr-0">
                                        <i class="uil-award-alt"></i> {{ $doctor['degree'] }}
                                    </li>
                                    <li class="list-inline-item mr-2">
                                        <i class="uil-bill"></i> {{ $doctor['price'] }} <small>ج م</small>
                                    </li>
                                </ul>

                                <h5 class="mb-3">{{ $doctor['name'] }}</h5>
                                <p>{{ Str::limit($doctor['description'], 150) }}</p>

                                <a href="{{ route('doctors.show', $doctor['id']) }}" class="btn btn-primary rounded-pill px-3">
                                    إقرأ المزيد <i class="uil-angle-left"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <i class="uil-frown" style="font-size: 1.5rem"></i>
                        <h5>لم يتم العثور على أى طبيب</h5>
                        <p>لا يوجد أطباء فى الوقت الحالى من فضلك حاول العثور عليهم فى وقت آخر.</p>
                    </div>
                @endif
            </div>
        </div>
@endsection
