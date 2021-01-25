<span
    {{ $attributes->merge([
        'class' => $badgeClass()
    ]) }}
    @if ($removeable)
        x-data="{ show: true }"
        x-show="show"
    @endif
>
    @if ($dot)
        <svg class="badge__dot" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
    @endif

    {{ $slot }}

    @if ($removeable)
        <button type="button"
                class="badge__remove-button"
                aria-label="{{ __('Remove') }}"
                x-on:click.prevent.stop="show = false; {{ $onRemoveClick ?? '' }}"
        >
            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"/>
            </svg>
        </button>
    @endif
</span>
