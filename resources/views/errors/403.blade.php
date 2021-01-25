<x-page title="Forbidden">
    <section id="banner" class="banner" role="banner">
        <div class="wrap">
            <h1 class="banner-slogan">Forbidden</h1>
            <p class="banner-intro">
                It appears that you don't have the right clearance to do this...
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
