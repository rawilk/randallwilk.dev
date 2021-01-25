<x-page title="Laravel, PHP and JavaScript Packages">
    <x-slot name="description">
        Here are some of my open source packages I've created.
    </x-slot>

    @include('front.pages.open-source.partials.banner-packages')

    <div class="wrap pb-10 lg:py-12 lg:px-8">
        <section>
            @include('front.pages.open-source.partials.packages-intro')
        </section>

        <x-inner-nav aside-class="lg:px-0 lg:py-0 py-6"
                     nav-class="md:sticky md:top-2"
                     content-class="lg:px-0 space-y-6"
        >
            @include('front.pages.open-source.partials.menu')

            <section>
                <livewire:repositories />
            </section>
        </x-inner-nav>
    </div>
</x-page>
