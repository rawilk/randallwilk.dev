<header class="relative bg-transparent sticky w-full top-0 flex-none z-[100] backface-hidden ease-[cubic-bezier(0.43,0.49,0.51,0.68)] duration-[.25s] | print:bg-transparent print:shadow-none"
        id="header"
        x-data="frontHeader"
        x-on:scroll.window.passive="handleScroll"
        x-bind:class="{ 'shadow-lg bg-white': scrolled, 'bg-transparent': ! scrolled }"
>
    <div class="wrap">
        <div class="flex items-center justify-between py-6 | md:justify-start md:space-x-10">
            {{-- logo --}}
            <div class="flex justify-start lg:w-0 lg:flex-1">
                <a href="{!! route('home') !!}" class="flex h-8 w-40 sm:h-10" title="{{ __('front.home_link_title') }}">
                    <span class="sr-only">{{ appName() }}</span>
                    <x-logo
                        type="dual"
                        class="h-full w-full max-w-full text-slate-600"
                    />
                </a>
            </div>

            {{-- mobile menu --}}
            @include('layouts.front.partials.navigation.mobile-navigation')

            {{-- desktop menu --}}
            <nav class="hidden space-x-6 md:flex">
                @include('layouts.front.partials.navigation.main-menu')
            </nav>

            <div class="hidden items-center justify-end md:flex md:flex-1 lg:w-0">
                @auth
                    <livewire:profile-navigation-menu view="layouts.admin.partials.profile-navigation" />
                @else
                    <a href="{!! route('login') !!}" class="text-base font-medium text-slate-500 hover:text-slate-900">
                        {!! __('front.menus.main.login') !!}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

@auth
    @include('layouts.partials.logout-form')
@endauth
