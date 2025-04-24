@extends('admin::layouts.master')

@section('title', 'تفاصيل الموعد')

@section('actions')
    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-primary">
        <i class="bi bi-pencil"></i> تعديل
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-title mb-4">معلومات المريض</h5>
                <dl class="row">
                    <dt class="col-sm-4">الاسم</dt>
                    <dd class="col-sm-8">{{ $appointment->patient->name }}</dd>

                    <dt class="col-sm-4">البريد الإلكتروني</dt>
                    <dd class="col-sm-8">{{ $appointment->patient->email }}</dd>

                    <dt class="col-sm-4">رقم الهاتف</dt>
                    <dd class="col-sm-8">{{ $appointment->patient->phone_number }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <h5 class="card-title mb-4">معلومات الطبيب</h5>
                <dl class="row">
                    <dt class="col-sm-4">الاسم</dt>
                    <dd class="col-sm-8">{{ $appointment->doctor->name }}</dd>

                    <dt class="col-sm-4">البريد الإلكتروني</dt>
                    <dd class="col-sm-8">{{ $appointment->doctor->email }}</dd>

                    <dt class="col-sm-4">رقم الهاتف</dt>
                    <dd class="col-sm-8">{{ $appointment->doctor->phone }}</dd>
                </dl>
            </div>
        </div>

        <hr>

        <div class="row mt-4">
            <div class="col-md-12">
                <h5 class="card-title mb-4">تفاصيل الموعد</h5>
                <dl class="row">
                    <dt class="col-sm-2">تاريخ الموعد</dt>
                    <dd class="col-sm-10">{{ $appointment->scheduled_at->format('Y-m-d H:i') }}</dd>

                    <dt class="col-sm-2">الحالة</dt>
                    <dd class="col-sm-10">
                        <span class="badge bg-{{ $appointment->status_color }}">
                            {{ $appointment->status_text }}
                        </span>
                    </dd>

                    <dt class="col-sm-2">ملاحظات</dt>
                    <dd class="col-sm-10">{{ $appointment->notes ?: 'لا توجد ملاحظات' }}</dd>
                </dl>
            </div>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">عودة</a>
        </div>
    </div>
</div>
@endsection
