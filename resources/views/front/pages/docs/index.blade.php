<x-page title="{{ __('front.docs.title') }}" description="{{ __('front.docs.banner_intro') }}">
    <x-front.page-banner>
        {{ __('front.docs.banner_title') }}

        <x-slot:content>
            <p class="banner-intro">{{ __('front.docs.banner_intro') }}</p>
        </x-slot:content>
    </x-front.page-banner>

    <div class="wrap space-y-20 pb-20">
        @foreach ($repositories->groupBy('category') as $category => $repositories)
            <x-front.section-list heading="{{ $category }}" id="{{ $category }}">
                @each('front.pages.docs.partials.repository', $repositories, 'repository')
            </x-front.section-list>
        @endforeach
    </div>

    @include('layouts.front.partials.support')
</x-page>
