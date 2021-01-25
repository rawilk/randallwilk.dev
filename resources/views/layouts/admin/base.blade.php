@php($title = $title ?? '')
@php($pageTitle = $pageTitle ?? '')
@php($showTitle = $showTitle ?? true)

@push('head')
    @include('layouts.partials.admin-styles')
@endpush

<x-app :title="$title">
    <div class="h-screen flex overflow-hidden bg-gray-50">
        {{-- mobile nav --}}
        @include('layouts.admin.partials.mobile-navigation')

        {{-- desktop nav --}}
        @include('layouts.admin.partials.main-navigation')

        {{-- main content area --}}
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            {{-- top "nav" --}}
            @include('layouts.admin.partials.header')

            <main class="flex-1 relative overflow-y-auto focus:outline-none" tabindex="0">
                {{-- impersonation notice --}}
                @include('layouts.partials.impersonate-banner')

                <div class="py-6">
                    {{-- title area --}}
                    @if ($pageTitle)
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            {{ $pageTitle }}
                        </div>
                    @elseif ($title && $showTitle)
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <h1 class="text-2xl font-semibold text-gray-900">{{ $title }}</h1>
                        </div>
                    @endif

                    <div class="max-w-7xl mx-auto pb-10 lg:px-8">
                        {{-- page content --}}
                        <div class="py-4 space-y-4">
                            <div>{{ $slot }}</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- notifications --}}
    <x-notification />
</x-app>
