<x-page title="Projects"
        description="Search in my open source projects, written in Laravel & JavaScript."
        :livewire="true"
>
    @include('front.pages.open-source.partials.menu')

    @include('front.pages.open-source.partials.banner-projects')

    <div class="section pt-0 section-fade">
        <livewire:repositories type="projects" />
    </div>
</x-page>
