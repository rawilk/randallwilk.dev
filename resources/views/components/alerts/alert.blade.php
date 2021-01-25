<div class="alert alert--{{ $type }} {{ $border ? 'alert--border' : '' }}"
     @if ($dismiss)
     x-data="{ open: true }"
     x-show="open"
    @endif
>
    <div class="flex">
        @if ($icon)
            <div class="flex-shrink-0">
                <x-dynamic-component :component="$iconComponent()" class="alert__icon" />
            </div>
        @endif

        <div class="{{ $icon ? 'ml-3' : '' }}">
            @if ($title)
                <h3 class="alert__title">
                    {{ $title }}
                </h3>
            @endif

            {{ $slot }}
        </div>

        @if ($dismiss)
            <div class="ml-auto pl-3">
                <div class="-mx-1 5 -my-1 5">
                    <button @click="open = false"
                            type="button"
                            class="alert__dismiss-btn"
                    >
                        <x-heroicon-s-x class="h-5 w-5" />

                        <span class="sr-only">{{ __('Dismiss') }}</span>
                    </button>
                </div>
            </div>
        @endif

    </div>
</div>
