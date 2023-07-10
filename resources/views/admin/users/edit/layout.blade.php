<x-admin-app :title="$title ?? ''">
    <x-slot:pageTitle>
        <x-layout.page-title>
            <span class="page-title">{{ $user->name }}</span>

            <x-slot:actions>
                <div class="hidden sm:block" x-data>
                    <x-button
                        :href="route('admin.users.index')"
                        color="slate"
                        left-icon="css-arrow-left"
                    >
                        {{ __('Back to users') }}
                    </x-button>
                </div>
            </x-slot:actions>
        </x-layout.page-title>
    </x-slot:pageTitle>

    <x-inner-nav sticky-offset="md:top-20">
        <x-slot:nav>
            {{-- account info --}}
            <x-inner-nav-item
                icon="heroicon-o-user-circle"
                :href="$user->edit_url"
            >
                {{ __('Account Information') }}
            </x-inner-nav-item>

            {{-- abilities --}}
            @canany(['assignRolesTo', 'assignPermissionsTo'], $user)
                <x-inner-nav-item
                    icon="heroicon-o-finger-print"
                    :href="$user->abilities_url"
                    :active="request()->routeIs('admin.users.edit.abilities')"
                >
                    {{ __('Abilities') }}
                </x-inner-nav-item>
            @endcanany
        </x-slot:nav>

        @yield('slot')
        {{ $slot ?? '' }}
    </x-inner-nav>
</x-admin-app>
