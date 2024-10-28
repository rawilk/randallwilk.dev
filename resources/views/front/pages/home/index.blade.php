<x-page
    title="Full-Stack Laravel Developer"
    :canonical="route('home')"
>
    <x-slot:description>
        Randall Wilk is a Full-Stack developer that builds websites & web applications using the TALL stack.
        He is based in Wausau WI, United States.
    </x-slot:description>

    @include('front.pages.home.partials.intro')
    @include('front.pages.home.partials.about')
    @include('front.pages.home.partials.skills')

    <x-slot:call-to-action>
        <x-layout.front.support-cta />
    </x-slot:call-to-action>
</x-page>
