<x-page title="Unauthorized">
    <section id="banner" class="banner" role="banner">
        <div class="wrap">
            <h1 class="banner-slogan">Entering private territory</h1>
            <p class="banner-intro">Seems like you don't have access to this page.</p>
        </div>
    </section>

    <div class="section section-group pt-0">
        @include('errors.partials.suggestions')

        <section class="section">
            <div class="wrap">
                <p class="text-2xl">
                    If you need to get in asap, just contact me so I can help you out.
                </p>

                @include('errors.partials.contact')
            </div>
        </section>
    </div>
</x-page>
