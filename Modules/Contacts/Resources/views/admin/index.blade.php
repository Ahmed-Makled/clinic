@extends('layouts.admin')

@section('title', 'إدارة رسائل الاتصال')

@section('header_icon')
    <i class="bi bi-envelope-open text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item active">رسائل الاتصال</li>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">الاسم</th>
                            <th scope="col">البريد الإلكتروني</th>
                            <th scope="col">الموضوع</th>
                            <th scope="col">الرسالة</th>
                            <th scope="col">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="fw-medium">{{ $contact->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $contact->email }}</td>
                                <td>
                                    <span class="text-primary fw-medium">{{ $contact->subject }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($contact->message, 80) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar2 text-primary me-2"></i>
                                        {{ $contact->created_at->format('Y-m-d') }}
                                        <br>
                                        <i class="bi bi-clock text-success mx-2"></i>
                                        {{ $contact->created_at->format('h:i A') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-envelope-x display-6 d-block mb-3 opacity-50"></i>
                                        <p class="h5 opacity-75">لا توجد رسائل اتصال</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($contacts->hasPages())
                <div class="pagination-wrapper mt-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="pagination-info">
                                <span class="text-muted">
                                    عرض {{ $contacts->firstItem() }} إلى {{ $contacts->lastItem() }} من أصل {{ $contacts->total() }} نتيجة
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                <ul class="pagination custom-pagination mb-0">
                                    {{-- First Page Link --}}
                                    @if (!$contacts->onFirstPage())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $contacts->url(1) }}" title="الصفحة الأولى">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Previous Page Link --}}
                                    @if ($contacts->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="bi bi-chevron-right"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $contacts->previousPageUrl() }}" title="الصفحة السابقة">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $start = max($contacts->currentPage() - 2, 1);
                                        $end = min($contacts->currentPage() + 2, $contacts->lastPage());

                                        $showStartDots = $start > 2;
                                        $showEndDots = $end < $contacts->lastPage() - 1;
                                    @endphp

                                    {{-- Always show first page if not in range --}}
                                    @if ($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $contacts->url(1) }}">1</a>
                                        </li>
                                        @if ($showStartDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $contacts->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $contacts->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Always show last page if not in range --}}
                                    @if ($end < $contacts->lastPage())
                                        @if ($showEndDots)
                                            <li class="page-item disabled">
                                                <span class="page-link dots">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $contacts->url($contacts->lastPage()) }}">{{ $contacts->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($contacts->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $contacts->nextPageUrl() }}" title="الصفحة التالية">
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
                                    @if ($contacts->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $contacts->url($contacts->lastPage()) }}" title="الصفحة الأخيرة">
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
                        <span class="text-muted">إجمالي النتائج: {{ $contacts->total() }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .avatar-circle {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 0.75rem;
                flex-shrink: 0;
            }

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
