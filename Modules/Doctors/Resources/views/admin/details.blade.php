@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('doctors.index') }}">الأطباء</a>
    </li>
    <li class="breadcrumb-item active">تفاصيل الطبيب</li>
@endsection

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-soft-primary">
            <i class="bi bi-pencil me-2"></i> تعديل البيانات
        </a>
        <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-soft-danger">
                <i class="bi bi-x-circle me-2"></i> حذف
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- بطاقة الملف الشخصي -->
        <div class="profile-card">
            <!-- معلومات الملف الشخصي الأساسية -->
            <div class="profile-info">
                <div class="profile-avatar">
                    @if($doctor->image)
                        <div class="avatar-wrapper">
                            <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" class="doctor-image"
                                onerror="this.src='{{ asset('images/default-doctor.png') }}'; this.onerror=null;">
                        </div>
                    @else
                        <div class="avatar-wrapper">
                            <div class="avatar-placeholder">
                                <i class="bi bi-person-badge fs-2"></i>
                            </div>
                        </div>
                    @endif
                    <div class="status-indicator {{ $doctor->status ? 'active' : 'inactive' }}">
                        <i class="bi {{ $doctor->status ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                    </div>
                </div>

                <div class="profile-details w-100">
                    <div class="d-flex justify-content-between align-items-start w-100">
                        <div>
                            <h1 class="doctor-name">@if($doctor->gender =='ذكر')دكتور @else دكتورة@endif {{ $doctor->name }}</h1>
                            <!-- التخصصات والحالة -->
                            <div class="badges">
                                <span class="specialty-badge">{{ $doctor->title }}</span>
                                @if($doctor->category)
                                    <span class="specialty-badge">{{ $doctor->category->name }}</span>
                                @endif
                                <span class="status-badge {{ $doctor->status ? 'active' : 'inactive' }}">
                                    {{ $doctor->status ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                            <!-- التقييم -->
                            <div class="rating-info mt-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($doctor->rating_avg))
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @elseif ($i - 0.5 <= $doctor->rating_avg)
                                                <i class="bi bi-star-half text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-muted">({{ $doctor->ratings_count }} تقييم)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بطاقات الإحصائيات -->
            <div class="stats-grid">
                <!-- رسوم الكشف -->
                <div class="stat-card">
                    <div class="stat-icon consultation">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">رسوم الكشف</div>
                        <div class="stat-value">{{ number_format($doctor->consultation_fee) }} جنيه</div>
                        <div class="stat-trend {{ $feeComparisonRate >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi {{ $feeComparisonRate >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                            {{ abs(round($feeComparisonRate)) }}% {{ $feeComparisonRate >= 0 ? 'أعلى من' : 'أقل من' }} متوسط التخصص
                        </div>
                    </div>
                </div>

                <!-- الحجوزات المكتملة -->
                <div class="stat-card">
                    <div class="stat-icon appointments">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">الحجوزات المكتملة</div>
                        <div class="stat-value">{{ $completedAppointments }}</div>
                        <div class="stat-trend {{ $completedGrowthRate >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi {{ $completedGrowthRate >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                            {{ abs(round($completedGrowthRate)) }}% {{ $completedGrowthRate >= 0 ? 'زيادة' : 'انخفاض' }} عن الشهر السابق
                        </div>
                    </div>
                </div>

                <!-- الحجوزات الملغاة -->
                <div class="stat-card">
                    <div class="stat-icon cancelled">
                        <i class="bi bi-calendar2-x"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">الحجوزات الملغاة</div>
                        <div class="stat-value">{{ $cancelledAppointments }}</div>
                        <div class="stat-trend neutral">
                            <i class="bi bi-calendar3"></i>
                            هذا الشهر
                        </div>
                    </div>
                </div>

                <!-- إجمالي الإيرادات -->
                <div class="stat-card">
                    <div class="stat-icon earnings">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">إجمالي الإيرادات</div>
                        <div class="stat-value">{{ $totalEarnings }} جنيه</div>
                        <div class="stat-trend {{ $earningsGrowthRate >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi {{ $earningsGrowthRate >= 0 ? 'bi-graph-up' : 'bi-graph-down' }}"></i>
                            {{ abs(round($earningsGrowthRate)) }}% {{ $earningsGrowthRate >= 0 ? 'نمو' : 'انخفاض' }} عن الشهر السابق
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الطبيب -->
            <div class="info-section">
                <h2 class="section-title">
                    <i class="bi bi-person-vcard me-2"></i>
                    المعلومات الأساسية
                </h2>
                <div class="info-grid">
                    <!-- المعلومات الشخصية -->
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="info-content">
                            <label>الاسم</label>
                            <span class="info-value">{{ $doctor->name }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-gender-ambiguous"></i>
                        </div>
                        <div class="info-content">
                            <label>الجنس</label>
                            <span class="info-value">{{ $doctor->gender }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="info-content">
                            <label>البريد الإلكتروني</label>
                            <span class="info-value">{{ $doctor->email }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="info-content">
                            <label>رقم الهاتف</label>
                            <span class="info-value">{{ $doctor->phone }}</span>
                        </div>
                    </div>
                </div>

                <!-- المعلومات المهنية -->
                <h2 class="section-title mt-4">
                    <i class="bi bi-briefcase me-2"></i>
                    المعلومات المهنية
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="info-content">
                            <label>المسمى الوظيفي</label>
                            <span class="info-value">{{ $doctor->title ?? 'غير محدد' }}</span>
                        </div>
                    </div>



                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="info-content">
                            <label>سنوات الخبرة</label>
                            <span class="info-value">{{ $doctor->experience_years ?? 'غير محدد' }} سنة</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="info-content">
                            <label>وقت الانتظار</label>
                            <span class="info-value">{{ $doctor->waiting_time ?? 'غير محدد' }} دقيقة</span>
                        </div>
                    </div>
                </div>

                <!-- معلومات العنوان -->
                <h2 class="section-title mt-4">
                    <i class="bi bi-geo-alt me-2"></i>
                    معلومات العنوان
                </h2>
                <div class="info-grid address">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="info-content">
                            <label>المحافظة والمدينة</label>
                            <span class="info-value">{{ $doctor->governorate->name }} - {{ $doctor->city->name }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="info-content">
                            <label>عنوان العيادة</label>
                            <span class="info-value">{{ $doctor->address ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                </div>

                <!-- الوصف والسيرة الذاتية -->
                @if($doctor->bio || $doctor->description)
                    <h2 class="section-title mt-4">
                        <i class="bi bi-file-text me-2"></i>
                        نبذة عن الطبيب
                    </h2>
                    @if($doctor->description)
                        <div class="bio-section content-card">
                            <div class="card-body pt-0">
                                <p>{{ $doctor->description }}</p>
                            </div>
                        </div>
                    @endif


                @endif
  <!-- جدول الحجوزات -->
  @if($doctor->schedules->isNotEmpty())
  <h2 class="section-title mt-4">
      <i class="bi bi-calendar-week me-2"></i>
      جدول الحجوزات
  </h2>
  <div class="schedule-grid">
      @foreach($doctor->schedules as $schedule)
          <div class="schedule-day {{ $schedule->is_active ? 'available' : 'unavailable' }}">
              <div class="day-header">
                  <span class="day-name">{{ $schedule->day_name }}</span>
                  <span class="availability-badge">
                      @if($schedule->is_active)
                          <i class="bi bi-check-circle-fill text-success"></i>
                          متاح
                      @else
                          <i class="bi bi-x-circle-fill text-danger"></i>
                          غير متاح
                      @endif
                  </span>
              </div>
              @if($schedule->is_active)
                  <div class="time-slots">
                      <div class="time-slot">
                          <i class="bi bi-clock"></i>
                          <span>{{ date('h:i A', strtotime($schedule->start_time)) }}</span>
                          <i class="bi bi-arrow-right"></i>
                          <span>{{ date('h:i A', strtotime($schedule->end_time)) }}</span>
                      </div>
                  </div>
              @endif
          </div>
      @endforeach
  </div>
@endif
                <!-- قسم التقييمات -->
                <h2 class="section-title mt-4">
                    <i class="bi bi-star me-2"></i>
                    التقييمات والمراجعات
                    <span class="rating-count">({{ $doctor->ratings_count }})</span>
                </h2>

                <div class="ratings-section">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="ratings-summary">
                                <div class="average-rating">
                                    <div class="rating-number">{{ number_format($doctor->rating_avg, 1) }}</div>
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($doctor->rating_avg))
                                                <i class="bi bi-star-fill"></i>
                                            @elseif ($i - 0.5 <= $doctor->rating_avg)
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="rating-count-text">بناءً على {{ $doctor->ratings_count }} تقييم</div>
                                </div>

                                <div class="rating-bars">
                                    @foreach($doctor->rating_stats as $stars => $data)
                                        <div class="rating-bar-item">
                                            <div class="star-label">{{ $stars }} <i class="bi bi-star-fill"></i></div>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $data['percentage'] }}%" aria-valuenow="{{ $data['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="rating-count">{{ $data['count'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="ratings-list">
                                @if($ratings->count() > 0)
                                    @foreach($ratings as $rating)
                                        <div class="rating-card">
                                            <div class="rating-header">
                                                <div class="reviewer-info">
                                                    <div class="reviewer-avatar">
                                                        <div class="avatar-text">{{ substr($rating->patient->user->name ?? 'م', 0, 2) }}</div>
                                                    </div>
                                                    <div class="reviewer-details">
                                                        <h5 class="reviewer-name">{{ $rating->patient->user->name ?? 'مريض' }}</h5>
                                                        <div class="rating-date">{{ $rating->created_at->locale('ar')->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                                <div class="rating-value">
                                                    <div class="stars">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $rating->rating)
                                                                <i class="bi bi-star-fill text-warning"></i>
                                                            @else
                                                                <i class="bi bi-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <div class="rating-text">{{ $rating->rating }}/5</div>
                                                </div>
                                            </div>
                                            @if($rating->comment)
                                                <div class="rating-content">
                                                    <p>{{ $rating->comment }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-ratings">
                                        <i class="bi bi-star text-muted"></i>
                                        <p>لا توجد تقييمات حتى الآن</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- الحجوزات -->
            @if($appointments->isNotEmpty())
                <div class="appointments-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title م-0">
                            <i class="bi bi-calendar2-week me-2"></i>
                            الحجوزات
                            <span class="appointments-count">({{ $appointments->count() }})</span>
                        </h2>
                    </div>
                    <div class="timeline">
                        @foreach($appointments as $appointment)
                            <div class="timeline-item {{ $appointment->status }}">
                                <div class="timeline-point"></div>
                                <div class="appointment-card">
                                    <div class="appointment-header">
                                        <div class="time-slot">
                                            <div class="time">
                                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}
                                            </div>
                                            <div class="duration">{{ $doctor->waiting_time ?? 30 }} دقيقة</div>
                                        </div>
                                        <div class="status {{ $appointment->status }}">
                                            <i class="bi bi-circle-fill"></i>
                                            {{ $appointment->status_text }}
                                        </div>
                                    </div>

                                    <div class="patient-info">
                                        <div class="patient-primary">
                                            <div class="avatar">{{ substr($appointment->patient->name, 0, 2) }}</div>
                                            <div>
                                                <h4 class="patient-name">{{ $appointment->patient->name }}</h4>
                                                <div class="patient-details">
                                                    <span class="detail-item">
                                                        <i class="bi bi-telephone"></i>
                                                        {{ $appointment->patient->user->phone_number }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="patient-meta">
                                            @if($appointment->fees)
                                                <div class="fees {{ $appointment->is_paid ? 'paid' : 'unpaid' }}">
                                                    <i
                                                        class="bi {{ $appointment->is_paid ? 'bi-check-circle' : 'bi-exclamation-circle' }}"></i>
                                                    <span>{{ $appointment->fees }} جنيه</span>
                                                    <small>({{ $appointment->is_paid ? 'مدفوع' : 'غير مدفوع' }})</small>
                                                </div>
                                            @endif
                                            @if($appointment->is_important)
                                                <div class="important-flag">
                                                    <i class="bi bi-star-fill"></i>
                                                    حجز مهم
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($appointment->notes)
                                        <div class="appointment-notes">
                                            <i class="bi bi-journal-text"></i>
                                            {{ $appointment->notes }}
                                        </div>
                                    @endif

                                    <div class="appointment-actions">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                            عرض التفاصيل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Base Profile Card Styles */
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        /* Profile Info Styles */
        .profile-info {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
            align-items: flex-start;
        }

        .profile-avatar {
            position: relative;
            flex-shrink: 0;
        }

        .avatar-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 15px;
            overflow: hidden;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.05) 0%, rgba(55, 65, 81, 0.1) 100%);
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .doctor-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        /* Status Indicator Styles */
        .status-indicator {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border: 3px solid white;
            transition: all 0.3s ease;
        }

        .status-indicator.active {
            background: linear-gradient(135deg, #38c172 0%, #2fb344 100%);
            color: white;
            box-shadow: 0 4px 8px rgba(56, 193, 114, 0.3);
        }

        .status-indicator.inactive {
            background: linear-gradient(135deg, #e3342f 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 8px rgba(227, 52, 47, 0.3);
        }

        /* Doctor Name and Badges */
        .doctor-name {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .specialty-badge, .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .specialty-badge {
            background: #e3f2fd;
            color: #0066cc;
        }

        .status-badge.active {
            background: #e8f5e9;
            color: #28a745;
        }

        .status-badge.inactive {
            background: #ffebee;
            color: #dc3545;
        }

        /* Stats Grid Styles */
        .stats-grid {
            display: flex;
            gap: 1.5rem;
            padding: 1rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(var(--bs-primary-rgb), 0.06);
            border: 1px solid rgba(var(--bs-primary-rgb), 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            width: max-content;
        }

        /* Stat Icon Styles */
        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .stat-icon.consultation {
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            color: var(--bs-primary);
        }

        .stat-icon.appointments {
            background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
            color: #38c172;
        }

        .stat-icon.cancelled {
            background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #e3342f;
        }

        .stat-icon.earnings {
            background: linear-gradient(135deg, rgba(246, 153, 63, 0.1) 0%, rgba(255, 139, 20, 0.1) 100%);
            color: #f59e0b;
        }

        /* Stat Details Styles */
        .stat-details {
            flex: 1;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.375rem;
        }

        .stat-value {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Stat Trend Styles */
        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            width: fit-content;
            text-wrap: nowrap;
        }

        .stat-trend.positive {
            background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
            color: #38c172;
        }

        .stat-trend.negative {
            background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #e3342f;
        }

        .stat-trend.neutral {
            background: linear-gradient(135deg, rgba(var(--bs-secondary-rgb), 0.1) 0%, rgba(108, 117, 125, 0.1) 100%);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.1);
        }

        /* Section Styles */
        .section-title {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: var(--bs-primary);
            font-size: 1.25rem;
        }

        .info-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(var(--bs-primary-rgb), 0.08);
        }

        /* Info Grid Styles */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .info-content label {
            display: block;
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .info-item {
            padding: 1.25rem;
            border-radius: 15px;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: all 0.3s ease;
        }


        .info-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, rgba(var(--bs-dark-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            color: var(--bs-dark);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            border: 1px solid rgba(var(--bs-dark-rgb), 0.1);
            transition: all 0.3s ease;
        }

        .card-body p {
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }
        /* Schedule Styles */
        .schedule-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(var(--bs-primary-rgb), 0.06);
            border: 1px solid rgba(var(--bs-primary-rgb), 0.08);
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .schedule-day {
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.03) 0%, rgba(37, 99, 235, 0.03) 100%);
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid rgba(var(--bs-primary-rgb), 0.08);
            transition: all 0.3s ease;
        }

        .schedule-day.unavailable {
            background: linear-gradient(135deg, rgba(var(--bs-danger-rgb), 0.03) 0%, rgba(220, 38, 38, 0.03) 100%);
            border: 1px solid rgba(var(--bs-danger-rgb), 0.08);
        }

        /* أنماط قسم التقييمات */
        .ratings-section {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background-color: #f9f9f9;
            border-radius: 15px;
        }

        .rating-count {
            font-size: 1rem;
            color: #6c757d;
            font-weight: normal;
        }

        .ratings-summary {
            background-color: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .average-rating {
            margin-bottom: 1.5rem;
        }

        .rating-number {
            font-size: 3rem;
            font-weight: bold;
            color: #212529;
            margin-bottom: 0.5rem;
        }

        .rating-stars {
            color: #ffc107;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .rating-count-text {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .rating-bars {
            margin-top: 1.5rem;
        }

        .rating-bar-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .star-label {
            width: 50px;
            text-align: right;
            color: #495057;
            font-size: 0.875rem;
            margin-left: 0.5rem;
        }

        .progress {
            flex-grow: 1;
            height: 0.5rem;
            border-radius: 1rem;
            background-color: #e9ecef;
            margin-left: 0.5rem;
        }

        .rating-count {
            width: 30px;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .ratings-list {
            max-height: 600px;
            overflow-y: auto;
            padding-left: 1rem;
        }

        .rating-card {
            background-color: white;
            border-radius: 15px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .rating-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
        }

        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
        }

        .avatar-text {
            font-weight: bold;
            font-size: 1rem;
        }

        .reviewer-name {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .rating-date {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .rating-value {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .rating-value .stars {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .rating-text {
            font-size: 0.75rem;
            color: #495057;
        }

        .rating-content {
            color: #495057;
            line-height: 1.5;
        }

        .rating-content p {
            margin: 0;
        }

        .empty-ratings {
            text-align: center;
            padding: 3rem 0;
            color: #6c757d;
        }

        .empty-ratings i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .profile-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .stats-grid {
                flex-direction: column;
            }

            .stat-card {
                width: 100%;
                padding: 1.25rem;
            }

            .info-grid, .schedule-grid {
                grid-template-columns: 1fr;
            }

            .stat-icon {
                width: 42px;
                height: 42px;
            }

            .info-icon {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .ratings-list {
                padding-left: 0;
                margin-top: 1.5rem;
            }
        }
    </style>
@endpush

