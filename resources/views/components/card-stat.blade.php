@props(['title', 'value', 'icon' => null, 'variant' => 'primary'])
<div class="col">
    <div class="card h-100 border-0 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <div class="me-3">
                @if($icon)
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 56px; height: 56px; background-color: var(--color-cream); color: var(--color-primary);">
                        <i class="bi bi-{{ $icon }} fs-4"></i>
                    </div>
                @endif
            </div>
            <div>
                <p class="text-muted mb-1 small">{{ $title }}</p>
                <h4 class="mb-0 fw-bold" style="color: #2c3e50;">{{ $value }}</h4>
            </div>
        </div>
    </div>
</div>
