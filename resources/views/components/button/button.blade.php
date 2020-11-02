@php($wireTarget = $attributes->wire('target')->value())

<div class="relative btn-container {{ $block ? 'w-full' : '' }} {{ $containerClass }}">
    <button
        {{ $attributes->merge(['type' => 'button', 'class' => "relative btn btn-{$variant}" . ($block ? ' w-full' : '') . ($icon ? ' btn-icon' : '')]) }}
        @if ($wireTarget)
            wire:loading.attr="disabled"
            wire:loading.class="btn-busy"
        @endif
    >
        {{ $slot }}

        @if ($wireTarget)
            <span wire:loading.delay
                  wire:loading.class="flex"
                  wire:target="{{ $wireTarget }}"
                  class="absolute h-4 w-4 top-0 right-0 -mt-1 -mr-1"
            >
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-pink-500"></span>
            </span>
        @endif
    </button>
</div>
