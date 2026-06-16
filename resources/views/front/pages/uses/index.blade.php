<x-page
    :title="__('front.uses.title')"
    :description="__('front.uses.description')"
>
    @include('front.pages.uses.partials.banner')

    <x-front.content-area>
        <div class="text-sm">
            {{
                str(__('front.uses.last_updated', ['date' => '<time datetime="2026-06-15">June 15, 2026</time>']))
                    ->inlineMarkdown()
                    ->toHtmlString()
            }}
        </div>

        <div>
            {{ str(__('front.uses.banner_intro'))->markdown()->toHtmlString() }}
        </div>

        <p class="italic">
            {{ str(__('front.uses.affiliate_disclosure'))->inlineMarkdown()->toHtmlString() }}
        </p>
    </x-front.content-area>

    <div class="wrap space-y-20 mt-16 sm:mt-20 pb-32">
        @include('front.pages.uses.partials.dev-tools')

        @include('front.pages.uses.partials.hardware')

        @include('front.pages.uses.partials.software')

        @include('front.pages.uses.partials.misc')
    </div>

    <x-slot:call-to-action>
        <x-layout.front.support-cta />
    </x-slot:call-to-action>
</x-page>
