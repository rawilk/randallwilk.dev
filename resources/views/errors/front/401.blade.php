<x-page title="Unauthorized">
    <x-front.page-banner>
        401

        <x-slot:content>
            <p class="banner-intro">
                {{ $exception->getMessage() ?: 'Unauthorized' }}
            </p>

            <p class="text-lg mt-4">
                Seems like you don't have access to this page.
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
