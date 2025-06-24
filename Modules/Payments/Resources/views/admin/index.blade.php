@extends('layouts.admin')

@section('title', 'إدارة المدفوعات')

@section('header_icon')
    <i class="bi bi-cash-coin text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item active">المدفوعات</li>
@endsection

@section('actions')
    <a href="{{ route('admin.payments.export') }}" class="btn btn-success btn-sm px-3">
        <i class="bi bi-file-earmark-excel me-1"></i> تصدير البيانات
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body position-relative">
            <!-- Enhanced Filters -->
            <div class="filters mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">حالة الدفع</label>
                        <select name="status" class="form-select select2" id="statusFilter">
                            <option value="">الكل</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>مرفوض</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترجع</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="paymentMethodFilter" class="form-label">طريقة الدفع</label>
                        <select name="payment_method" class="form-select select2" id="paymentMethodFilter">
                            <option value="">الكل</option>
                            <option value="stripe" {{ request('payment_method') == 'stripe' ? 'selected' : '' }}>بطاقة ائتمان</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="startDate" class="form-label">من تاريخ</label>
                        <input type="date" name="start_date" id="startDate" class="form-control" value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="endDate" class="form-label">إلى تاريخ</label>
                        <input type="date" name="end_date" id="endDate" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="searchInput" class="form-label">بحث</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search"
                                   class="form-control"
                                   id="searchInput"
                                   name="search"
                                   placeholder="رقم المعاملة..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="button" class="btn btn-primary" id="applyFilters">
                            <i class="bi bi-funnel-fill me-1"></i> تطبيق الفلتر
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">رقم المعاملة</th>
                            <th scope="col">المريض</th>
                            <th scope="col">الطبيب</th>
                            <th scope="col">المبلغ</th>
                            <th scope="col">الحالة</th>
                            <th scope="col">طريقة الدفع</th>
                            <th scope="col">تاريخ العملية</th>
                            <th scope="col">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="transaction-id">{{ $payment->transaction_id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($payment->appointment && $payment->appointment->patient)
                                            <div>
                                                {{ $payment->appointment->patient->name }}
                                            </div>
                                        @else
                                            <div>غير متوفر</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($payment->appointment && $payment->appointment->doctor)
                                            @if($payment->appointment->doctor->image)
                                                <img src="{{ $payment->appointment->doctor->image_url }}"
                                                     alt="{{ $payment->appointment->doctor->name }}"
                                                     class="doctor-avatar">
                                            @endif
                                            <div>
                                                {{ $payment->appointment->doctor->name }}
                                            </div>
                                        @else
                                            <div>غير متوفر</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ number_format($payment->amount) }}</strong> {{ $payment->currency }}
                                </td>
                                <td>
                                    @if ($payment->status == 'completed')
                                        <span class="status-badge active">
                                            <i class="bi bi-check-circle-fill"></i> مكتمل
                                        </span>
                                    @elseif ($payment->status == 'pending')
                                        <span class="status-badge pending">
                                            <i class="bi bi-clock-fill"></i> معلق
                                        </span>
                                    @elseif ($payment->status == 'failed')
                                        <span class="status-badge inactive">
                                            <i class="bi bi-x-circle-fill"></i> مرفوض
                                        </span>
                                    @elseif ($payment->status == 'refunded')
                                        <span class="status-badge refunded">
                                            <i class="bi bi-arrow-return-left"></i> مسترجع
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($payment->payment_method == 'stripe')
                                        <span class="payment-method stripe">
                                            <i class="bi bi-credit-card me-1"></i> بطاقة ائتمان
                                        </span>
                                    @elseif ($payment->payment_method == 'cash')
                                        <span class="payment-method cash">
                                            <i class="bi bi-cash me-1"></i> نقدي
                                        </span>
                                    @else
                                        {{ $payment->payment_method }}
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar2 text-primary me-2"></i>
                                        {{ $payment->created_at->format('Y-m-d') }}
                                        <br>
                                        <i class="bi bi-clock text-success mx-2"></i>
                                        {{ $payment->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="btn-action btn-view"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="عرض">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-credit-card display-6 d-block mb-3 opacity-50"></i>
                                        <p class="h5 opacity-75">لا توجد مدفوعات</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="pagination-wrapper mt-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="pagination-info">
                                <span class="text-muted">
                                    عرض {{ $payments->firstItem() }} إلى {{ $payments->lastItem() }} من أصل {{ $payments->total() }} نتيجة
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                <ul class="pagination custom-pagination mb-0">
                                    {{-- First Page Link --}}
                                    @if (!$payments->onFirstPage())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $payments->url(1) }}" title="الصفحة الأولى">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Previous Page Link --}}
                                    @if ($payments->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $payments->previousPageUrl() }}" title="الصفحة السابقة">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $start = max($payments->currentPage() - 2, 1);
                                        $end = min($payments->currentPage() + 2, $payments->lastPage());

                                        $showStartDots = $start > 2;
                                        $showEndDots = $end < $payments->lastPage() - 1;
                                    @endphp

                                    {{-- Always show first page if not in range --}}
                                    @if ($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $payments->url(1) }}">1</a>
                                        </li>
                                        @if ($showStartDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $payments->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $payments->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Always show last page if not in range --}}
                                    @if ($end < $payments->lastPage())
                                        @if ($showEndDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $payments->url($payments->lastPage()) }}">{{ $payments->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($payments->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $payments->nextPageUrl() }}" title="الصفحة التالية">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-left"></i>
                                            </span>
                                        </li>
                                    @endif

                                    {{-- Last Page Link --}}
                                    @if ($payments->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $payments->url($payments->lastPage()) }}" title="الصفحة الأخيرة">
                                                <i class="bi bi-chevron-double-left"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            @else
                <div class="pagination-wrapper mt-4">
                    <div class="text-center">
                        <span class="text-muted">إجمالي النتائج: {{ $payments->total() }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Select2
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });

                // الحصول على عناصر الفلتر
                const statusFilter = document.getElementById('statusFilter');
                const paymentMethodFilter = document.getElementById('paymentMethodFilter');
                const startDate = document.getElementById('startDate');
                const endDate = document.getElementById('endDate');
                const searchInput = document.getElementById('searchInput');
                const applyFiltersBtn = document.getElementById('applyFilters');

                // دالة لتحديث الفلاتر
                function updateFilters() {
                    const params = new URLSearchParams(window.location.search);

                    // فحص وإضافة الفلاتر فقط إذا كانت لها قيمة
                    if (statusFilter?.value?.trim()) {
                        params.set('status', statusFilter.value.trim());
                    } else {
                        params.delete('status');
                    }

                    if (paymentMethodFilter?.value?.trim()) {
                        params.set('payment_method', paymentMethodFilter.value.trim());
                    } else {
                        params.delete('payment_method');
                    }

                    if (startDate?.value?.trim()) {
                        params.set('start_date', startDate.value.trim());
                    } else {
                        params.delete('start_date');
                    }

                    if (endDate?.value?.trim()) {
                        params.set('end_date', endDate.value.trim());
                    } else {
                        params.delete('end_date');
                    }

                    if (searchInput?.value?.trim()) {
                        params.set('search', searchInput.value.trim());
                    } else {
                        params.delete('search');
                    }

                    // تحديث الرابط مع الفلاتر
                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                }

                // إضافة مستمع الحدث لزر تطبيق الفلاتر
                applyFiltersBtn.addEventListener('click', updateFilters);

                // إضافة مستمع حدث للبحث عند الضغط على Enter
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        updateFilters();
                    }
                });

                // تفعيل tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush

    @push('styles')
    <style>
        .form-label {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: var(--bs-gray-700);
        }

        .filters {
            background: var(--bs-gray-100);
            border-radius: 0.5rem;
            padding: 1.25rem;
        }

        .table th {
            white-space: nowrap;
        }

        /* Transaction ID style */
        .transaction-id {
            font-family: monospace;
            font-weight: 600;
            color: var(--bs-primary);
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.875rem;
            border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
        }

        /* Doctor avatar */
        .doctor-avatar {
            width: 32px;
            height: 32px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 0.75rem;
        }

        /* Status badge styles */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-badge.active {
            background-color: var(--success-bg-subtle);
            color: var(--success-color);
        }

        .status-badge.inactive {
            background-color: var(--danger-bg-subtle);
            color: var(--danger-color);
        }

        .status-badge.pending {
            background-color: var(--warning-bg-subtle);
            color: var(--warning-color);
        }

        .status-badge.refunded {
            background-color: var(--info-bg-subtle);
            color: var (--info-color);
        }

        /* Payment method styles */
        .payment-method {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.875rem;
        }

        .payment-method.stripe {
            color: #6772e5;
        }

        .payment-method.cash {
            color: #28a745;
        }

        /* Enhanced action buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: var(--secondary-color);
            transition: all var(--transition-speed) ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-action.btn-view:hover {
            background: var(--info-bg-subtle);
            color: var(--info-color);
        }

        .btn-action.btn-mark-completed:hover {
            background: var(--success-bg-subtle);
            color: var(--success-color);
        }

        .btn-action.btn-mark-failed:hover {
            background: var(--danger-bg-subtle);
            color: var(--danger-color);
        }

        /* Modal enhancements */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* Pagination styles */
        .pagination-wrapper {
            padding-top: 1.5rem;
            border-top: 1px solid var(--bs-gray-200);
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 0 0 0.5rem 0.5rem;
            padding: 1.5rem;
            margin: 0 -1.5rem -1.5rem -1.5rem;
        }

        .pagination-info {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .custom-pagination {
            margin: 0;
            gap: 0.25rem;
        }

        .custom-pagination .page-item {
            margin: 0;
        }

        .custom-pagination .page-link {
            border: 1px solid var(--bs-gray-300);
            color: var(--bs-gray-700);
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            background: white;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .custom-pagination .page-item:first-child .page-link,
        .custom-pagination .page-item:last-child .page-link {
            min-width: 2.5rem;
        }

        .custom-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #0056b3 100%);
            border-color: var(--bs-primary);
            color: white;
            box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.3);
            transform: translateY(-1px);
        }

        .custom-pagination .page-link:hover:not(.disabled) {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.2);
        }

        .custom-pagination .page-item.disabled .page-link {
            background-color: var(--bs-gray-100);
            border-color: var(--bs-gray-200);
            color: var(--bs-gray-400);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .custom-pagination .page-link.dots {
            border: none;
            background: transparent;
            box-shadow: none;
            color: var(--bs-gray-500);
            cursor: default;
            font-weight: 600;
            letter-spacing: 0.1em;
        }

        .custom-pagination .page-item.disabled .page-link.dots {
            background: transparent;
            border: none;
        }

        .custom-pagination .page-link i {
            font-size: 0.75rem;
            line-height: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .pagination-wrapper .row {
                flex-direction: column-reverse;
                gap: 1rem;
            }

            .pagination-wrapper .col-md-6 {
                text-align: center;
            }

            .custom-pagination {
                justify-content: center;
                flex-wrap: wrap;
            }

            .custom-pagination .page-link {
                padding: 0.375rem 0.5rem;
                min-width: 2rem;
                height: 2rem;
                font-size: 0.8rem;
            }

            .pagination-info {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .custom-pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
                display: none;
            }
        }
    </style>
    @endpush
@endsection
