<x-page
    :title="__('front.uses.title')"
    :description="__('front.uses.description')"
>
    @include('front.pages.uses.partials.banner')

    <x-front.content-area>
        <p>{!! Str::inlineMarkdown(__('front.uses.banner_intro')) !!}</p>

        <p class="italic">
            {!! Str::inlineMarkdown(__('front.uses.affiliate_disclosure')) !!}
        </p>
    </x-front.content-area>

    <div class="wrap space-y-20 mt-16 sm:mt-20 pb-20">
        @include('front.pages.uses.partials.dev-tools')

        @include('front.pages.uses.partials.hardware')

        @include('front.pages.uses.partials.software')

        @include('front.pages.uses.partials.misc')
    </div>

    <div class="sm:mt-20 pb-32">
        @include('front.pages.uses.partials.outro')
    </div>
</x-page>
