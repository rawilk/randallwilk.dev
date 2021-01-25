<div x-data="{ open: false }"
     x-on:set-nav-open.window="open = $event.detail"
     x-on:keydown.escape.window="$dispatch('set-nav-open', false)"
     x-on:click.away="$dispatch('set-nav-open', false)"
     x-transition:enter="duration-200 ease-out"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="duration-100 ease-in"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     x-show="open"
     class="absolute z-top top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden"
>
    <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-200">
        <div class="pt-5 pb-6 px-5">

            <div class="flex items-center justify-between">
                {{-- logo --}}
                <div>
                    <a href="{!! route('home') !!}">
                        <img class="h-8 w-auto" src="{!! asset('images/logo.png') !!}" alt="{{ __('front.logo_alt') }}">
                    </a>
                </div>

                {{-- toggle button --}}
                <div class="-mr-2">
                    <button x-on:click="$dispatch('set-nav-open', false)"
                            type="button"
                            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
                    >
                        <span class="sr-only">{{ __('front.menus.mobile.close_menu') }}</span>
                        <x-heroicon-s-x class="h-6 w-6" />
                    </button>
                </div>
            </div>

            <div class="mt-6">
                {{-- main menu --}}
                <nav class="grid gap-y-8">
                    {!! Menu::main()
                            ->withoutWrapperTag()
                            ->withoutParentTag()
                            ->addItemClass('-m-3 p-3 flex items-center rounded-md text-base font-medium text-gray-900 hover:bg-gray-100 focus:outline-blue-gray')
                            ->setActiveClass('font-semibold bg-gray-100')
                            ->setActiveClassOnLink()
                    !!}
                </nav>
            </div>
        </div>

        <div class="py-6 px-5 space-y-6">
            @auth
                <livewire:profile.profile-navigation view-name="layouts.partials.navigation.mobile-profile-navigation" />
            @else
                <div>
                    <p class="text-center text-base font-medium text-blue-gray-500">
                        Have an account?
                        <a href="{!! route('login') !!}" class="link-black">
                            Sign in
                        </a>
                    </p>
                </div>
            @endauth
        </div>
    </div>
</div>
