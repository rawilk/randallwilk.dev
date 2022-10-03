<x-page
    title="{{ __('front.open_source.packages.title') }}"
    description="{{ __('front.open_source.packages.description') }}"
>
    @include('front.pages.open-source.partials.banner-packages')

    <div class="section section-group pt-0">
        @include('front.pages.open-source.partials.packages-intro')

        <livewire:front.repositories />
    </div>

    @include('layouts.front.partials.support')
</x-page>
