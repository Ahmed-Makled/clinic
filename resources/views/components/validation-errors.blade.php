@if ($errors->any())
    <div class="alert alert-danger alert-validation-error mb-4">
        <div class="alert-body">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <span class="fw-bold">يرجى تصحيح الأخطاء التالية:</span>
            <ul class="mt-2 mb-0 pe-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
