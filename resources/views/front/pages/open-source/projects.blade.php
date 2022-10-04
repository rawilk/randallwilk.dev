<x-page
    title="{{ __('front.open_source.projects.title') }}"
    description="{{ __('front.open_source.projects.description') }}"
>
    @include('front.pages.open-source.partials.banner-projects')

    <div class="section section-group pt-0">
        @include('front.pages.open-source.partials.projects-intro')

        <livewire:front.repositories type="project" />
    </div>

    @include('layouts.front.partials.support')
</x-page>
