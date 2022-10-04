<x-laravel-base::layouts.html
    :title="$title ?? ''"
    class="bg-white dark:bg-slate-900"
>
    <x-slot:html class="antialiased [font-feature-settings:'ss01']" id="docsScreen"></x-slot:html>

    <x-slot:headTop>
        @include('layouts.front.partials.analytics')
        @include('layouts.front.partials.meta')
        <link rel="preconnect" href="https://{{ config('services.algolia.app_id') }}-dsn.algolia.net" crossorigin />
    </x-slot:headTop>

    <x-slot:head>
        @stack('head')
        @include('layouts.docs.partials.assets')
    </x-slot:head>

    @include('layouts.docs.partials.header')

    {{ $hero ?? '' }}

    <main class="relative mx-auto flex max-w-8xl justify-center sm:px-2 lg:px-8 xl:px-12">
        {{-- nav --}}
        @include('layouts.docs.partials.navigation')

        {{-- content --}}
        <div class="min-w-0 max-w-2xl flex-auto px-4 py-16 lg:max-w-none lg:pr-0 lg:pl-8 xl:px-16">
            {{ $slot }}
        </div>

        {{-- table of contents --}}
        @include('layouts.docs.partials.table-of-contents')
    </main>

    <x-scroll-to-top-button />

    @lbJavaScript
</x-laravel-base::layouts.html>
