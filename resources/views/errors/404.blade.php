<x-page title="Page not found">
    <section id="banner" class="banner" role="banner">
        <div class="wrap">
            <h1 class="banner-slogan">Page not found</h1>
            <p class="banner-intro">
                There could be a typo in your url, or the link you are using could be deprecated...
            </p>
        </div>
    </section>

    <div class="section pt-0">
        @include('errors.partials.suggestions')

        <section class="section pt-0">
            <div class="wrap">
                <p class="text-2xl">
                    If you still need help, just contact me so I can help you out.
                </p>

                @include('errors.partials.contact')
            </div>
        </section>
    </div>
</x-page>
