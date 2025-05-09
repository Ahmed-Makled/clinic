@if ($errors->any())
    <div class="alert-card error mb-4">
        <div class="alert-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <h6 class="alert-heading">يرجى تصحيح الأخطاء التالية:</h6>
            <ul class="mt-2 mb-0 pe-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
            <i class="bi bi-x"></i>
        </button>
    </div>
@endif
