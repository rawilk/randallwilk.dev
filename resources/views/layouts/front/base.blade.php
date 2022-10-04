<x-app
    :title="$title ?? ''"
    class="flex flex-col min-h-screen"
>
    <x-slot:head-top>
        @include('layouts.front.partials.analytics')
        @include('layouts.front.partials.meta')
    </x-slot:head-top>

    <x-slot:head>
        @include('layouts.front.partials.assets')
    </x-slot:head>

    @include('layouts.front.partials.header')

    <main class="flex-grow">
        @if (request()->hasSession())
            @include('laravel-base::partials.auth.impersonate-banner')
        @endif

        {{ $slot }}
    </main>

    @includeWhen($showFooter ?? true, 'layouts.front.partials.footer')

    <x-scroll-to-top-button />
</x-app>
