<x-page title="{{ __('errors.401.title') }}">
    <x-front.page-banner>
        401

        <x-slot:content>
            <p class="banner-intro">{{ $exception->getMessage() ?: __('errors.401.title') }}</p>

            {{-- if we don't have an exception message, we're probably on the front-end --}}
            @unless ($exception->getMessage())
                <p class="text-lg mt-4">
                    {{ __('errors.401.description') }}
                </p>
            @endunless
        </x-slot:content>
    </x-front.page-banner>

    <div class="section section-group pt-0">
        @include('errors.partials.suggestions')

        <section class="section">
            <div class="wrap markup">
                <p class="text-lg !mb-2">{{ __('errors.contact_title') }}</p>
                @include('errors.partials.contact')
            </div>
        </section>
    </div>
</x-page>
