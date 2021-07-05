<a href="{{ $href }}"
   {{ $attributes->merge(['class' => $linkClass()]) }}
>
    @if ($icon)
        <x-dynamic-component :component="$icon"
                             class="{{ $iconClass() }}"
                             aria-hidden="true"
        />
    @endif

    <span class="truncate w-full">{{ $slot }}</span>
</a>
