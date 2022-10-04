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
        @include('laravel-base::partials.auth.impersonate-banner')

        {{ $slot }}
    </main>

    @include('layouts.front.partials.footer')

    <x-scroll-to-top-button />
</x-app>
