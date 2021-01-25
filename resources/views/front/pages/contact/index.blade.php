<x-page title="Contact">
    <x-slot name="description">
        Contact me on {!! config('site.contact.email') !!}, or on one of my social media profiles.
    </x-slot>

    <section id="banner" class="banner" role="banner">
        <div class="wrap">
            <h1 class="banner-slogan">Contact</h1>
        </div>
    </section>

    <div class="section pt-0">
        @include('front.pages.contact.partials.contact')
    </div>
</x-page>
