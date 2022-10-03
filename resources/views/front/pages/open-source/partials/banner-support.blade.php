<x-front.page-banner>
    {!! Str::inlineMarkdown(__('front.open_source.support.banner_title')) !!}

    <x-slot:content>
        <p class="banner-intro">{{ __('front.open_source.support.banner_intro') }}</p>
    </x-slot:content>
</x-front.page-banner>
