<x-page
    :title="__('Site Map')"
    :description="__('Use the site map to find your way around :url.', ['url' => url('/')])"
>
    @include('front.pages.sitemap.partials.banner')

    <div class="wrap mt-8">
        <div class="[&_a]:text-blue-600 [&_a:hover]:text-blue-500 [&_a:hover]:underline">
            @include('front.pages.sitemap.partials.main-links')

            @include('front.pages.sitemap.partials.doc-links')
        </div>
    </div>
</x-page>
