<x-page title="{{ __('errors.503.title') }}" :show-footer="false">
    <x-front.page-banner>
        {!! Str::inlineMarkdown(__('errors.503.slogan')) !!}

        <x-slot:content>
            <p class="banner-intro">{!! Str::inlineMarkdown(__('errors.503.description')) !!}</p>
        </x-slot:content>
    </x-front.page-banner>

    <div class="section section-group pt-0">
        <section class="section">
            <div class="wrap markup">
                <p class="text-lg !mb-2">{!! Str::inlineMarkdown(__('errors.503.contact_title')) !!}</p>
                @include('errors.partials.contact')
            </div>
        </section>
    </div>
</x-page>
