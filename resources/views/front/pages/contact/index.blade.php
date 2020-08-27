<x-page title="Contact">
    <x-slot name="description">
        Contact me on {!! config('site.contact.email') !!}, or on one of my social media profiles.
    </x-slot>

    <div class="section section-group pt-10">
        @include('front.pages.contact.partials.contact')
    </div>
</x-page>
