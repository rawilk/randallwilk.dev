<div class="h-100">
    <div class="featured-box featured-box-primary featured-box-effect-3 h-100">
        <div class="box-content">
            @if (isset($icon))
                <i class="icon-featured far {{ $icon }}"></i>
            @endif
            <h4 class="font-weight-normal text-5">
                {{ $title }}
            </h4>
            {{ $slot }}
        </div>
    </div>
</div>

