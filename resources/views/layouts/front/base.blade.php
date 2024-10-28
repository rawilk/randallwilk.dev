@props([
    'title' => '',
    'canonical' => null,
    'ogTitle' => null,
    'ogDescription' => null,
    'ogImage' => null,
    'description' => '',
    'showHeader' => true,
    'showFooter' => true,
    'needsLivewireScripts' => true,
    'callToAction' => null,
])

@php
    $fullTitle = filled($title)
        ? App\Helpers\formatPageTitle($title, config('app.name'))
        : config('app.name');
@endphp

<x-layout.html
    :title="$fullTitle"
    class="flex flex-col"
>
    <x-slot:head-start>
        <x-layout.partials.analytics :id="config('services.google.analytics.id')" />
        <x-layout.partials.meta
            :canonical="$canonical"
            :title="$title"
            :description="$description"
            :og-description="$ogDescription"
            :og-title="$ogTitle"
            :og-image="$ogImage"
        />
    </x-slot:head-start>

    <x-slot:head>
        @include('layouts.front.partials.assets')

        @if ($needsLivewireScripts)
            @livewireStyles
        @endif
    </x-slot:head>

    @if ($showHeader)
        <x-layout.front.header />
    @endif

    <main class="flex-grow">
        {{ $slot }}
    </main>

    {{ $callToAction }}

    @if ($showFooter)
        <x-layout.front.footer />
    @endif

    @if ($needsLivewireScripts)
        @livewireScripts
    @endif
</x-layout.html>
