<x-page
    :title="__('front.uses.archive.sep-2022.title')"
    :description="__('front.uses.description')"
>
    @include('front.pages.archive.uses.sep-2022.partials.banner')

    <x-front.content-area>
        <p>{!! Str::inlineMarkdown(__('front.uses.archive.sep-2022.banner_intro')) !!}</p>

        <p class="italic">
            {!! Str::inlineMarkdown(__('front.uses.affiliate_disclosure')) !!}
        </p>
    </x-front.content-area>

    <div class="wrap space-y-20 mt-16 sm:mt-20 pb-20">
        @include('front.pages.archive.uses.sep-2022.partials.dev-tools')

        @include('front.pages.archive.uses.sep-2022.partials.hardware')

        @include('front.pages.archive.uses.sep-2022.partials.software')

        @include('front.pages.archive.uses.sep-2022.partials.misc')
    </div>

    <div class="sm:mt-20 pb-32">
        @include('front.pages.archive.uses.sep-2022.partials.outro')
    </div>
</x-page>
