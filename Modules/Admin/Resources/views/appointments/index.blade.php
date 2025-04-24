@extends('admin::layouts.master')

@section('title', 'إدارة المواعيد')

@section('actions')
    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> موعد جديد
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المريض</th>
                        <th>الطبيب</th>
                        <th>الموعد</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->patient->name }}</td>
                            <td>{{ $appointment->doctor->name }}</td>
                            <td>{{ $appointment->scheduled_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $appointment->status_color }}">
                                    {{ $appointment->status_text }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.appointments.show', $appointment) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.appointments.edit', $appointment) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.appointments.destroy', $appointment) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger delete-confirmation">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد مواعيد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection
