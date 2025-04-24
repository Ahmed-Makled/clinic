@extends('admin::layouts.master')

@section('content')
<div class="container mt-5 pt-5" style="background-color: #F7F8FA; font-family: 'Tajawal', sans-serif;">
    <h1 class="mb-4 fw-bold" style="color: #2D3748;">لوحة التحكم</h1>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100" style="background-color: #DCEEFB; border: none; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-badge fs-4 me-2" style="color: #3182CE;"></i>
                        <h5 class="card-title mb-0" style="color: #2D3748;">الأطباء</h5>
                    </div>
                    <p class="card-text display-4 fw-bold mb-0" style="color: #3182CE;">{{ $stats['doctors'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100" style="background-color: #E3F9E5; border: none; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-people fs-4 me-2" style="color: #38A169;"></i>
                        <h5 class="card-title mb-0" style="color: #2D3748;">المرضى</h5>
                    </div>
                    <p class="card-text display-4 fw-bold mb-0" style="color: #38A169;">{{ $stats['patients'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100" style="background-color: #FFF4E5; border: none; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-check fs-4 me-2" style="color: #DD6B20;"></i>
                        <h5 class="card-title mb-0" style="color: #2D3748;">المواعيد</h5>
                    </div>
                    <p class="card-text display-4 fw-bold mb-0" style="color: #DD6B20;">{{ $stats['appointments'] }}</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
