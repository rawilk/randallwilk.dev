<header class="relative bg-white shadow-sm border-b border-blue-gray-200">
    <div class="wrap">
        <div class="flex justify-between items-center py-6 md:justify-start md:space-x-10">
            {{-- logo --}}
            <div class="flex justify-start lg:w-0 lg:flex-1">
                <a href="{!! route('home') !!}">
                    <img class="h-8 w-auto sm:h-10" src="{!! asset('images/logo.png') !!}" alt="{{ __('front.logo_alt') }}">
                </a>
            </div>

            {{-- mobile menu toggle --}}
            <div class="-mr-2 -my-2 md:hidden">
                <button x-data="{ open: false }"
                        x-on:set-nav-open.window="open = $event.detail"
                        x-init="$watch('open', value => { $dispatch('set-nav-open', value) })"
                        x-on:click="open = ! open"
                        x-bind:aria-expanded="JSON.stringify(open)"
                        type="button"
                        class="bg-gray-50 rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
                >
                    <span class="sr-only">{{ __('front.menus.mobile.open_menu') }}</span>
                    <x-heroicon-s-menu class="h-6 w-6" />
                </button>
            </div>

            {{-- main menu --}}
            <nav class="hidden md:flex space-x-10">
                @include('layouts.partials.menu')
            </nav>

            {{-- "service" menu --}}
            <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
                @include('layouts.partials.service')
            </div>
        </div>
    </div>

    {{-- mobile menu --}}
    @include('layouts.partials.mobile-menu')
</header>
