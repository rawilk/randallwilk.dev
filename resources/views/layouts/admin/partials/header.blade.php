<nav class="bg-white shadow">
    <div class="mx-auto pr-2 sm:pr-4 lg:pr-8">
        <div class="flex justify-between h-16">
            <div class="flex pr-2 lg:pr-0">
                <div class="flex-shrink-0 flex items-center">
                    @include('layouts.admin.partials.menu-toggle')
                </div>
            </div>

            {{-- mobile menu toggle --}}
            <div class="flex items-center lg:hidden">
                @include('layouts.admin.partials.header-menu-toggle')
            </div>

            <div class="hidden lg:ml-4 lg:flex lg:items-center">
                {{-- user profile dropdown --}}
                <div class="ml-4 relative flex-shrink-0">
                    <livewire:admin.profile-navigation />
                </div>
            </div>
        </div>
    </div>

    {{-- mobile menu --}}
    <div x-data="{ open: false }"
         x-on:set-secondary-nav-open.window="open = $event.detail"
         x-on:keydown.escape.window="open = false"
         x-on:click.away="open = false"
         x-init="$watch('open', value => { $dispatch('set-secondary-nav-open', value) })"
         class="lg:hidden"
         x-bind:class="{ 'block': open, 'hidden': ! open }"
    >
        <div class="pt-4 pb-3 border-t border-gray-200">
            <livewire:admin.profile-navigation view-name="layouts.admin.partials.mobile-profile-navigation" />

            {{-- user menu --}}
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.show') }}"
                   class="block px-4 py-2 text-base font-medium text-blue-gray-500 hover:text-blue-gray-800 hover:bg-blue-gray-100 focus:outline-blue-gray"
                >
                    {{ __('users.profile.account_info.page_title') }}
                </a>

                @can('viewHorizon')
                    <a href="{{ route('horizon.index') }}"
                       class="block px-4 py-2 text-base font-medium text-blue-gray-500 hover:text-blue-gray-800 hover:bg-blue-gray-100 focus:outline-blue-gray"
                    >
                        Horizon
                    </a>
                @endcan

                <button type="submit"
                        form="logout-form"
                        class="block w-full text-left px-4 py-2 text-base font-medium text-blue-gray-500 hover:text-blue-gray-800 hover:bg-blue-gray-100 focus:outline-blue-gray"
                >
                    {{ __('front.menus.service.logout') }}
                </button>
            </div>
        </div>
    </div>
</nav>
