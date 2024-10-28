<x-page title="Forbidden">
    <x-front.page-banner>
        403

        <x-slot:content>
            <p class="banner-intro">
                {{ $exception->getMessage() ?: 'Forbidden' }}
            </p>

            <p class="text-lg mt-4">
                It appears you don't have the right clearance to do this...
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
