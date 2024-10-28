<section class="section pt-0">
    <ul class="space-y-10 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:gap-y-12 sm:space-y-0 lg:grid-cols-3 lg:gap-x-8">
        {{-- general --}}
        <x-front.sitemap-link-section :title="__('General')">
            <x-front.sitemap-link :url="route('home')">{{ __('Home') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('contact')">{{ __('Contact') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('uses')">{{ __('Uses') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('sitemap')">{{ __('Site Map') }}</x-front.sitemap-link>
        </x-front.sitemap-link-section>

        {{-- open source --}}
        <x-front.sitemap-link-section :title="__('Open Source')">
            <x-front.sitemap-link :url="route('open-source.packages')">{{ __('Open Source Packages') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('open-source.projects')">{{ __('Open Source Projects') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('docs')">{{ __('Docs') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('open-source.support')">{{ __('Support Me') }}</x-front.sitemap-link>
        </x-front.sitemap-link-section>

        {{-- legal --}}
        <x-front.sitemap-link-section :title="__('Legal')">
            <x-front.sitemap-link :url="route('legal.terms')">{{ __('Terms of Use') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('legal.privacy')">{{ __('Privacy Policy') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('legal.disclaimer')">{{ __('Disclaimers') }}</x-front.sitemap-link>
            <x-front.sitemap-link :url="route('legal.index')">{{ __('Legal Overview') }}</x-front.sitemap-link>
        </x-front.sitemap-link-section>
    </ul>
</section>
