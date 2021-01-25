@props([
    'dark' => false,
    'containerClass' => '',
])

@php($wireTarget = $attributes->wire('target')->value())

<span class="relative inline-flex {{ $containerClass }}">
    <button
        {{ $attributes->merge(['class' => 'relative app-link' . ($dark ? ' app-link--dark' : ''), 'type' => 'button']) }}
        @if ($wireTarget)
            wire:loading.attr="disabled"
            wire:loading.class="btn-busy"
        @endif
    >
        {{ $slot }}
    </button>

    @if ($wireTarget)
        <span wire:loading.class.delay="opacity-100"
              wire:target="{{ $wireTarget }}"
              class="absolute flex h-3 w-3 top-0 right-0 -mt-2 -mr-2 opacity-0 transition-opacity"
        >
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
        </span>
    @endif
</span>
