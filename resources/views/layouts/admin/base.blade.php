@include('layouts.partials.page-scripts-and-styles')

@php($title = $title ?? '')
@php($pageTitle = $pageTitle ?? '')
@php($showTitle = $showTitle ?? true)

<x-app :title="$title">
    <div class="min-h-screen flex bg-gray-50">
        {{-- mobile nav --}}
        @include('layouts.admin.partials.mobile-navigation')

        {{-- desktop nav --}}
        @include('layouts.admin.partials.main-navigation')

        {{-- main content area --}}
        <div class="flex flex-col w-0 flex-1">
            {{-- top "nav" --}}
            @include('layouts.admin.partials.header')

            <main class="flex-1 relative focus:outline-none" id="main-content-wrapper" tabindex="0">
                {{-- impersonation notice --}}
                @include('laravel-base::partials.auth.impersonate-banner')

                <div class="py-6">
                    {{-- title area --}}
                    @unless ($showTitle === false)
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            @if ($pageTitle)
                                {{ $pageTitle }}
                            @elseif ($title)
                                <h1 class="text-2xl font-semibold text-slate-900">{{ $title }}</h1>
                            @endif
                        </div>
                    @endunless

                    <div class="max-w-7xl mx-auto pb-10 lg:px-8">
                        {{-- page content --}}
                        <div class="pb-4 lg:pt-4 space-y-4">
                            @include('layouts.partials.session-alert', ['canDismissAlert' => true])

                            <div>{{ $slot }}</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <x-notification />
    <x-scroll-to-top-button />
</x-app>
