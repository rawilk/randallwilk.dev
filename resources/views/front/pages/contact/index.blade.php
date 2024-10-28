{{--<x-page title="{{ __('front.contact.title') }}">--}}
{{--    <x-slot:description>--}}
{{--        {{ __('front.contact.description', ['email' => config('site.contact.email')]) }}--}}
{{--    </x-slot:description>--}}

{{--    @include('front.pages.contact.partials.banner')--}}

{{--    <div class="mt-4 section section-group">--}}
{{--        @include('front.pages.contact.partials.contact')--}}
{{--    </div>--}}

{{--    @include('layouts.front.partials.support')--}}
{{--</x-page>--}}

<x-page
    :title="__('front.contact.title')"
>
    <x-slot:description>
        {{ __('front.contact.description', ['email' => config('randallwilk.contact.email')]) }}
    </x-slot:description>

    @include('front.pages.contact.partials.banner')

    <div class="mt-4 section section-group">
        @include('front.pages.contact.partials.contact')
    </div>

    <x-slot:call-to-action>
        <x-layout.front.support-cta />
    </x-slot:call-to-action>
</x-page>
