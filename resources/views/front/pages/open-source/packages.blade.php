<x-page
    title="Laravel, PHP and JavaScript Packages"
    :livewire="true"
>
    <x-slot name="description">
        Here are some of my open source packages I've created.
    </x-slot>

    @include('front.pages.open-source.partials.menu')

    @include('front.pages.open-source.partials.banner-packages')

    <div class="section section-group pt-0">
        <section class="section">
            @include('front.pages.open-source.partials.packages-intro')

            <livewire:repositories />
        </section>
    </div>
</x-page>
