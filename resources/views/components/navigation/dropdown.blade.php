<div x-data="dropdown({ {{ $configToJson() }} })"
     x-on:keydown.escape.prevent.window="closeMenu"
     x-on:click.outside="closeMenu(false)"
     x-on:keydown.tab.prevent="closeMenu"
     x-on:keydown.arrow-down.prevent="focusNext"
     x-on:keydown.arrow-up.prevent="focusPrevious"
     x-on:keydown.home.prevent="focusFirst"
     x-on:keydown.end.prevent="focusLast"
     wire:ignore.self
     wire:key="drop-{{ \Illuminate\Support\Str::random(3) }}-{{ time() }}"
     {{ $attributes->class('relative inline-block text-left') }}
>
    {{-- trigger --}}
    <div class="w-full">
        {{-- default trigger button --}}
        @includeWhen($triggerText && ! $splitButton, 'components.navigation.partials.dropdown-trigger')

        {{-- default trigger button - split button --}}
        @includeWhen($triggerText && $splitButton, 'components.navigation.partials.dropdown-trigger-split')

        {{-- custom trigger --}}
        @includeWhen(! $triggerText && ! $splitButton, 'components.navigation.partials.dropdown-trigger-custom')

        {{-- custom trigger - split button --}}
        @includeWhen(! $triggerText && $splitButton, 'components.navigation.partials.dropdown-trigger-custom-split')
    </div>

    {{-- menu --}}
    <div x-ref="menu"
         x-cloak
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-bind:aria-hidden="JSON.stringify(! open)"
         x-bind:class="{ 'invisible': ! open }"
         class="dropdown-menu z-top absolute origin-top-right right-0 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none min-w-48 {{ $widthClass }}"
         role="menu"
         aria-orientation="vertical"
         @unless ($id === false)
            id="{{ $menuId() }}"
            aria-labelledby="{{ $triggerId() }}"
         @endunless
         tabindex="-1"
    >
        <div role="none">
            @includeWhen($withBackground, 'components.navigation.partials.dropdown-menu-standard')

            @unless ($withBackground)
                {{ $slot }}
            @endunless
        </div>
    </div>
</div>
