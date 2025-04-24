@extends('layouts.app')

@section('content')
<div class="container mt-5 pt-5">
    <h1 class="mb-4">لوحة التحكم</h1>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">الأطباء</h5>
                    <p class="card-text display-4">{{ $stats['doctors'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">المرضى</h5>
                    <p class="card-text display-4">{{ $stats['patients'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">المواعيد</h5>
                    <p class="card-text display-4">{{ $stats['appointments'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    إدارة النظام
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.doctors.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-person-badge me-2"></i>إدارة الأطباء
                        </a>
                        <a href="{{ route('admin.patients.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-people me-2"></i>إدارة المرضى
                        </a>
                        <a href="{{ route('admin.appointments.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-calendar-check me-2"></i>إدارة المواعيد
                        </a>
                        <a href="{{ route('admin.specialties.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-briefcase-medical me-2"></i>إدارة التخصصات
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-person me-2"></i>إدارة المستخدمين
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
