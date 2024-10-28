<x-page title="Page not found">
    <x-front.page-banner>
        404

        <x-slot:content>
            <p class="banner-intro">
                {{ $exception->getMessage() ?: 'Page not found' }}
            </p>

            <p class="text-lg mt-4">
                The resource you are looking for could have been removed, had its name changed or is temporarily unavailable.
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
