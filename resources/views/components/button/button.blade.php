@php($wireTarget = $attributes->wire('target')->value())

<span class="{{ $containerClass() }}">
    <{{ $tag() }}
        @if ($href)
            href="{{ $href }}"
            {{ $attributes->merge(['class' => $buttonClass()]) }}
        @else
            {{ $attributes->merge(['type' => 'button', 'class' => $buttonClass()]) }}
        @endif
        @if ($wireTarget)
                wire:loading.attr="disabled"
                wire:loading.class="button--busy"
        @endif
    >
        {{ $slot }}
    </{{ $tag() }}>

    @if ($wireTarget)
        <span wire:loading.class.delay="opacity-100"
              wire:target="{{ $wireTarget }}"
              class="absolute flex h-3 w-3 top-0 right-0 -mt-1 -mr-1 opacity-0 transition-opacity"
        >
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
        </span>
    @endif
</span>
