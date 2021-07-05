<div class="relative z-0 flex">
    <div class="flex-1 max-w-full" style="width: calc(100% - 3rem)">
        {{ $trigger ?? '' }}
    </div>

    <span class="-ml-px relative block flex-shrink-0">
        <x-button :variant="$buttonVariant"
                  icon
                  :size="$size"
                  class="rounded-l-none"
                  :extra-attributes="$triggerAttributes()"
        >
            <span class="sr-only">{{ __('Open menu') }}</span>
            <x-heroicon-s-chevron-down aria-hidden="true" />
        </x-button>
    </span>
</div>
