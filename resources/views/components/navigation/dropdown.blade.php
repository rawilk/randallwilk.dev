<div x-data="dropdown({
        fixed: {{ $fixedPosition ? 'true' : 'false' }},
     })"
     x-init="init()"
     x-on:click.away="open = false"
     x-on:keydown.escape.window="open = false"
     x-on:keydown.arrow-down.prevent="onArrowDown()"
     x-on:keydown.arrow-up.prevent="onArrowUp()"
     x-on:keydown.home.prevent="onHome()"
     x-on:keydown.end.prevent="onEnd()"
     wire:ignore.self
     {{ $attributes->merge(['class' => 'relative']) }}
>
    <div x-ref="trigger"
         x-on:click="toggleMenu()"
         x-bind:aria-expanded="JSON.stringify(open)"
         aria-haspopup="true"
    >
        @if ($triggerText)
            <x-button variant="white">
                <span>{{ $triggerText }}</span>

                <x-heroicon-s-chevron-down class="transform transition" x-bind:class="{ 'rotate-180': open }" />
            </x-button>
        @else
            {{ $trigger ?? '' }}
        @endif
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         x-cloak
         @unless ($withBackground) x-ref="menu" @endunless
         class="{{ $fixedPosition ? 'fixed z-top' : 'absolute z-10' }} mt-2 @if (! $fixedPosition) {{ $right ? 'origin-top-right right-0' : 'origin-top-left' }} @endif"
         x-bind:style="menuStyle"
    >
        @if ($withBackground)
            <div x-ref="menu" class="text-left rounded-md shadow-lg max-w-full min-w-48 bg-white py-1">
        @endif

        {{ $slot }}

        @if ($withBackground)
            </div>
        @endif
    </div>
</div>
