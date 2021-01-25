<x-admin-app :title="$title ?? ''">
    <x-slot name="pageTitle">
        <x-admin-page-title>
            <span class="page-title">{{ $user->name->full }}</span>

            <x-slot name="actions">
                <div class="hidden sm:block">
                    <x-button variant="white" href="{{ route('admin.users') }}">
                        <x-heroicon-s-arrow-left />
                        <span>{{ __('labels.back_button') }}</span>
                    </x-button>
                </div>
            </x-slot>
        </x-admin-page-title>
    </x-slot>

    <x-inner-nav aside-class="px-4 lg:px-0 lg:py-0 py-6"
                 nav-class="md:sticky md:top-2"
                 content-class="lg:px-0 space-y-6"
    >
        <x-slot name="nav">
            <x-inner-nav-item icon="heroicon-o-user-circle"
                              href="{{ $user->edit_url }}"
            >
                {{ __('users.tabs.account_info') }}
            </x-inner-nav-item>
        </x-slot>

        @yield('slot')
        {{ $slot ?? '' }}
    </x-inner-nav>
</x-admin-app>
