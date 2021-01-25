<x-page title="Projects"
        description="Search in my open source projects, written in Laravel & JavaScript."
>
    @include('front.pages.open-source.partials.banner-projects')

    <div class="wrap pb-10 py-12 lg:px-8">
        <x-inner-nav aside-class="lg:px-0 lg:py-0 py-6"
                     nav-class="md:sticky md:top-2"
                     content-class="lg:px-0 space-y-6"
        >
            @include('front.pages.open-source.partials.menu')

            <section>
                <livewire:repositories type="projects" />
            </section>
        </x-inner-nav>
    </div>
</x-page>
