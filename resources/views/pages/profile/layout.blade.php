<x-page title="{{ $title ?? '' }}">
    <section id="banner" class="banner lg:pb-8 lg:pt-4 bg-gray-50" role="banner">
        <div class="wrap">
            <x-breadcrumbs />

            <h1 class="banner-slogan mt-2">
                {{ $title ?? '' }}
            </h1>
        </div>
    </section>

    <div class="bg-gray-50">
        <div class="wrap pb-10 lg:pt-4 lg:pb-12">
            <x-inner-nav sticky-offset="md:top-24">
                <x-slot:nav>
                    <x-inner-nav-item
                        href="{{ route('profile.show') }}"
                        icon="css-user"
                    >
                        {{ __('users.profile.tabs.account') }}
                    </x-inner-nav-item>

                    <x-inner-nav-item
                        href="{{ route('profile.authentication') }}"
                        icon="css-lock"
                    >
                        {{ __('users.profile.tabs.authentication') }}
                    </x-inner-nav-item>
                </x-slot:nav>

                @yield('slot')
            </x-inner-nav>
        </div>
    </div>
</x-page>
