@php($title = $title ?? '')
@php($showHeader = $showHeader ?? true)

@push('head-top')
    @include('layouts.partials.analytics')
    @include('layouts.partials.meta')
@endpush

@push('head')
    @include('layouts.partials.front-styles')
@endpush

<x-app :title="$title">
    <div class="flex flex-col min-h-screen bg-gray-50">
        @if ($showHeader)
            {{-- header --}}
            @include('layouts.partials.header')

            @include('layouts.partials.impersonate-banner')
        @endif

        {{-- body --}}
        <main class="flex-grow" role="main">
            {{ $slot }}
        </main>

        {{-- footer --}}
        @include('layouts.partials.footer')
    </div>

    {{-- notifications --}}
    <x-notification />
</x-app>
