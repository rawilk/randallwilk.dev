<div class="-my-2 -mr-2 md:hidden"
     x-data="{ open: false }"
     x-cloak
     x-on:keydown.escape.window="open = false"
>
    <button type="button"
            class="inline-flex items-center justify-center rounded-md bg-white p-2 text-slate-400 hover:bg-gray-100 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
            x-on:click="open = true"
    >
        <span class="sr-only">Open menu</span>
        <x-heroicon-s-bars-3 class="h-6 w-6" />
    </button>

    <template x-teleport="#header">
        <div class="fixed z-[999] inset-0 md:hidden backdrop-blur bg-slate-600/50"
             x-show="open"
             x-on:click="open = false"
        >
        </div>
    </template>

    <template x-teleport="#header">
        <div class="absolute z-[1000] inset-x-0 top-0 origin-top-right transform p-2 transition md:hidden"
             x-show="open"
             x-transition:enter="duration-200 ease-out"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="duration-100 ease-in"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-on:click.outside="open = false"
        >
            <div class="divide-y-2 divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="px-5 pt-5">
                    <div class="flex items-center justify-between">
                        {{-- logo --}}
                        <div class="h-8 w-auto">
                            <x-logo type="mark-dual"
                                    class="w-full h-full max-w-full text-slate-600"
                            />
                        </div>

                        {{-- close button --}}
                        <div class="-mr-2">
                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-md bg-white p-2 text-slate-400 hover:bg-gray-100 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                                    x-on:click="open = false;document.body.classList.remove('overflow-hidden')"
                            >
                                <span class="sr-only">Close menu</span>
                                <x-heroicon-o-x-mark class="h-6 w-6" />
                            </button>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="py-6 px-5 pb-6">
                            <nav class="grid grid-cols-2 gap-y-8 gap-x-10">
                                {!! Menu::main(['mobile' => true])
                                    ->withoutWrapperTag()
                                    ->withoutParentTag()
                                    ->addItemClass('-m-3 p-3 flex items-center rounded-md text-base font-medium text-slate-900 hover:[&:not(.exact-active)]:bg-gray-100')
                                    ->setActiveClass('active [&.exact-active]:bg-gray-100')
                                    ->setActiveClassOnLink()
                                !!}
                            </nav>

                            @guest
                                <div class="mt-6">
                                    <div>
                                        <p class="text-center text-base font-medium text-slate-500">
                                            <span>{!! __('front.menus.main.mobile_login_title') !!}</span>
                                            <a href="{!! route('login') !!}" class="text-blue-600 hover:text-blue-500">
                                                {!! __('front.menus.main.login') !!}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>

                @auth
                    <div class="px-5 pt-5 pb-8">
                        <div class="px-5">
                            <livewire:profile-navigation-menu view="layouts.front.partials.navigation.mobile-profile-navigation" />
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </template>
</div>
