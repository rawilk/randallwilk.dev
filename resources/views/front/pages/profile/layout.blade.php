<x-page :title="$title ?? ''">
    <section id="banner" class="banner lg:pb-0" role="banner">
        <div class="wrap">
            <x-breadcrumbs />

            <h1 class="banner-slogan mt-2">
                {{ $title }}
            </h1>
        </div>
    </section>

    <div class="wrap pb-10 lg:py-12 px-0 lg:px-8">
        <x-inner-nav aside-class="px-4 lg:px-0 lg:py-0 py-6"
                     nav-class="md:sticky md:top-2"
                     content-class="lg:px-0 space-y-6"
        >
            <x-slot name="nav">
                <x-inner-nav-item href="{!! route('profile.show') !!}"
                                  icon="css-user"
                >
                    {{ __('users.profile.tabs.account_info') }}
                </x-inner-nav-item>

                <x-inner-nav-item href="{!! route('profile.authentication') !!}"
                                  icon="css-lock"
                >
                    {{ __('users.profile.tabs.authentication') }}
                </x-inner-nav-item>
            </x-slot>

            @yield('slot')
            {{ $slot ?? '' }}
        </x-inner-nav>
    </div>
</x-page>
