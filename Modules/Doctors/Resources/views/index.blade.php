@extends('layouts.app')

@section('content')
<section class="about-page doctors-index mt-5 py-5">
    <div class="container mt-5 pt-5">
        <h1 class="mb-5">الأطباء</h1>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           id="searchInput"
                           placeholder="ابحث عن طبيب...">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="categoryFilter">
                    <option value="">كل التخصصات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($doctors->count() > 0)
            <div class="row">
                @foreach($doctors as $doctor)
                    <div class="col-md-4 mb-4">
                        <div class="doctor-card card h-100">
                            <div class="card-body text-center">
                                @if($doctor->image)
                                    <img src="{{ asset('storage/' . $doctor->image) }}"
                                         alt="{{ $doctor->name }}"
                                         class="doctor-avatar mb-3">
                                @else
                                    <div class="default-avatar mb-3">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                @endif
                                <h4 class="doctor-name">{{ $doctor->name }}</h4>
                                <div class="doctor-specialties mb-2">
                                    @foreach($doctor->categories as $category)
                                        <span class="badge bg-info">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                                <p class="doctor-bio">{{ $doctor->bio ?: 'لا يوجد وصف متاح' }}</p>
                                <a href="{{ route('doctors.show', $doctor->id) }}"
                                   class="btn btn-primary mt-2">عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                لا يوجد أطباء متاحين حالياً
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    const searchInput = $('#searchInput');
    const categoryFilter = $('#categoryFilter');
    const doctorCards = $('.doctor-card');

    function filterDoctors() {
        const searchText = searchInput.val().toLowerCase();
        const selectedCategory = categoryFilter.val();

        doctorCards.each(function() {
            const card = $(this);
            const doctorName = card.find('.doctor-name').text().toLowerCase();
            const doctorBio = card.find('.doctor-bio').text().toLowerCase();
            const categories = card.find('.badge').map(function() {
                return $(this).text().toLowerCase();
            }).get();

            const matchesSearch = doctorName.includes(searchText) ||
                                doctorBio.includes(searchText);

            const matchesCategory = !selectedCategory ||
                categories.some(cat => cat === selectedCategory.toLowerCase());

            card.closest('.col-md-4').toggleClass('d-none',
                !(matchesSearch && matchesCategory)
            );
        });

        const hasVisibleDoctors = doctorCards.closest('.col-md-4')
            .not('.d-none').length > 0;

        if (!hasVisibleDoctors) {
            if (!$('.no-results-message').length) {
                $('.row').append(
                    '<div class="col-12 text-center no-results-message">' +
                    '<div class="alert alert-info">لا توجد نتائج مطابقة للبحث</div>' +
                    '</div>'
                );
            }
        } else {
            $('.no-results-message').remove();
        }
    }

    searchInput.on('keyup', filterDoctors);
    categoryFilter.on('change', filterDoctors);
});
</script>
@endpush
@endsection
