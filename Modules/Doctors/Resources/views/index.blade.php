@extends('layouts.app')

@section('content')
<section class="about-page doctors-index mt-5 py-5">
    <div class="container mt-5 pt-5">
        <h1 class="mb-5 text-center">الأطباء المتخصصين</h1>

        <div class="row mb-4 justify-content-center">
            <div class="col-md-4">
                <div class="input-group search-group">
                    <input type="text"
                           class="form-control custom-search"
                           id="searchInput"
                           placeholder="ابحث عن طبيب...">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select custom-select" id="categoryFilter">
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
                        <div class="doctor-card">
                            <div class="doctor-header">
                                @if($doctor->image)
                                    <img src="{{ asset('storage/' . $doctor->image) }}"
                                         alt="{{ $doctor->name }}"
                                         class="doctor-avatar">
                                @else
                                    <div class="default-avatar">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="doctor-body">
                                <h4 class="doctor-name">{{ $doctor->name }}</h4>
                                <div class="doctor-title">{{ $doctor->title }}</div>
                                <div class="doctor-specialties">
                                    @foreach($doctor->categories as $category)
                                        <span class="specialty-badge">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                                <div class="doctor-info">
                                    @if($doctor->experience_years)
                                        <div class="info-item">
                                            <i class="bi bi-calendar-check"></i>
                                            <span>{{ $doctor->experience_years }} سنوات خبرة</span>
                                        </div>
                                    @endif
                                    @if($doctor->price)
                                        <div class="info-item">
                                            <i class="bi bi-cash"></i>
                                            <span>{{ $doctor->price }} ج.م</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="doctor-bio">{{ Str::limit($doctor->bio, 100) ?: 'لا يوجد وصف متاح' }}</p>
                                <a href="{{ route('doctors.show', $doctor->id) }}"
                                   class="btn btn-primary w-100">عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>
                لا يوجد أطباء متاحين حالياً
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
.search-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border-radius: 8px;
    overflow: hidden;
}

.custom-search {
    border: none;
    padding: 12px 15px;
}

.custom-select {
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.doctor-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.doctor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.doctor-header {
    position: relative;
    background: linear-gradient(45deg, #4a90e2, #63b3ed);
    padding: 2rem;
    text-align: center;
}

.doctor-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    object-fit: cover;
}

.default-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.default-avatar i {
    font-size: 3rem;
    color: #718096;
}

.doctor-body {
    padding: 1.5rem;
}

.doctor-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    text-align: center;
}

.doctor-title {
    color: #718096;
    font-size: 0.9rem;
    text-align: center;
    margin-bottom: 1rem;
}

.doctor-specialties {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
    margin-bottom: 1rem;
}

.specialty-badge {
    background: #ebf4ff;
    color: #4a90e2;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.875rem;
}

.doctor-info {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f7fafc;
    border-radius: 8px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4a5568;
}

.info-item i {
    color: #4a90e2;
}

.doctor-bio {
    color: #718096;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    text-align: center;
}

.btn-primary {
    background: #4a90e2;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #357abd;
}
</style>
@endpush

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
            const categories = card.find('.specialty-badge').map(function() {
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
                    '<div class="alert alert-info">' +
                    '<i class="bi bi-info-circle me-2"></i>' +
                    'لا توجد نتائج مطابقة للبحث</div>' +
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
