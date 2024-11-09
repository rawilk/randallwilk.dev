<x-page title="Server error">
    <x-front.page-banner>
        500

        <x-slot:content>
            <p class="banner-intro">
                Server error
            </p>

            <p class="text-lg mt-4">
                My server seems to have a little building this page...<br>
                I'll get to the bottom of this asap!
            </p>
        </x-slot:content>
    </x-front.page-banner>

    <div class="section section-group pt-0">
        <x-errors.suggestions />

        <section class="section">
            <div class="wrap markup">
                <x-errors.contact />
            </div>
        </section>
    </div>
</x-page>
