<x-page title="Server Error">
    <section id="banner" class="banner" role="banner">
        <div class="wrap">
            <h1 class="banner-slogan">We've encountered an error</h1>
            <p class="banner-intro">
                My server seems to have a little trouble building this page...<br>
                I'll get to the bottom of this asap!
            </p>
        </div>
    </section>

    <div class="section section-group pt-0">
        @include('errors.partials.suggestions')

        <section class="section">
            <div class="wrap">
                <p class="text-2xl">
                    If you still need help, just contact me so I can help you out.
                </p>

                @include('errors.partials.contact')
            </div>
        </section>
    </div>
</x-page>
