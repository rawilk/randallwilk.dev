<span class="relative z-0 inline-flex">
    <x-button :variant="$buttonVariant"
              :size="$size"
              class="rounded-r-none"
    >
        <span>{{ $triggerText }}</span>
    </x-button>

    <span class="-ml-px relative block">
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
</span>
