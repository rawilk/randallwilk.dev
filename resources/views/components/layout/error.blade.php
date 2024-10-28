@props([
    'title' => '',
    'icon' => 'heroicon-m-exclamation-triangle',
    'code' => '',
    'codeMessage' => null,
    'message' => null,
    'codeExplanation' => null,
    'visitorInstructions' => null,
    'ownerInstructions' => null,
    'color' => 'danger',
])

@php
    $codeMessage ??= $title;
    $linkColor = \Filament\Support\Colors\Color::Blue;
@endphp

    <!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
    @class([
        'fi min-h-screen',
        'dark' => filament()->hasDarkModeForced(),
    ])
>
    <head>
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_START) }}

        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @if ($favicon = filament()->getFavicon())
            <link rel="icon" href="{{ $favicon }}" />
        @endif

        <title>{{ $title }} - {{ filament()->getBrandName() }}</title>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_BEFORE) }}

        <style>
            [x-cloak=''],
            [x-cloak='x-cloak'],
            [x-cloak='1'] {
                display: none !important;
            }

            @media (max-width: 1023px) {
                [x-cloak='-lg'] {
                    display: none !important;
                }
            }

            @media (min-width: 1024px) {
                [x-cloak='lg'] {
                    display: none !important;
                }
            }
        </style>

        @filamentStyles

        {{ filament()->getTheme()->getHtml() }}
        {{ filament()->getFontHtml() }}

        <style>
            :root {
                --font-family: {!! filament()->getFontFamily() !!};
                --sidebar-width: {{ filament()->getSidebarWidth() }};
                --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
                --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
            }
        </style>

        @stack('styles')

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_AFTER) }}

        @if (! filament()->hasDarkMode())
            <script>
                localStorage.setItem('theme', 'light')
            </script>
        @elseif (filament()->hasDarkModeForced())
            <script>
                localStorage.setItem('theme', 'dark')
            </script>
        @else
            <script>
                const theme = localStorage.getItem('theme') ?? 'system'

                if (
                    theme === 'dark' ||
                    (theme === 'system' &&
                        window.matchMedia('(prefers-color-scheme: dark)')
                            .matches)
                ) {
                    document.documentElement.classList.add('dark')
                }
            </script>
        @endif

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_END) }}
    </head>
    <body
        class="fi-body min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white"
    >
        <main class="min-h-screen">
            <div class="grid lg:grid-cols-2 xl:grid-cols-5 h-full">
                <div
                    class="bg-custom-500 dark:bg-custom-600 lg:min-h-screen flex justify-center items-center xl:col-span-2"
                    @style(\Filament\Support\get_color_css_variables($color, [500, 600]))
                >
                    <div class="py-4 lg:py-0">
                        <x-filament::icon
                            :icon="$icon"
                            class="h-20 w-20 lg:h-64 lg:w-64 text-gray-800 opacity-75"
                        />
                    </div>
                </div>

                <div class="xl:col-span-3 lg:flex lg:items-center px-4 lg:px-10 py-6 lg:py-0 bg:white dark:bg-gray-700">
                    <div
                        class="max-w-3xl text-gray-800 dark:text-white [&_a]:text-custom-600 [&_a]:underline dark:[&_a]:text-custom-400 [&_a:hover]:text-custom-500 dark:[&_a:hover]:text-custom-300 [&_a:hover]:transition-colors"
                        @style(\Filament\Support\get_color_css_variables($linkColor, [300, 400, 500, 600]))
                    >

                        @if ($code)
                            <h1 class="text-2xl md:text-6xl font-bold">{{ $code }}</h1>
                        @endif

                        @if ($codeMessage)
                            <h2 class="text-xl md:text-2xl mt-1">{{ $codeMessage }}</h2>
                        @endif

                        @if ($message)
                            <p class="text-lg mt-4">
                                {{ $message }}
                            </p>
                        @endif

                        {{ $slot }}

                        @if ($codeExplanation)
                            <div class="mt-6">
                                <div class="text-base font-medium">{{ __('messages.errors.code_explanation_title') }}</div>
                                <p class="text-base mt-1">
                                    {{ $codeExplanation }}
                                </p>
                            </div>
                        @endif

                        @if ($visitorInstructions || $ownerInstructions)
                            <div class="mt-8">
                                <div class="text-lg font-medium">{{ __('messages.errors.instructions_title') }}</div>

                                @if ($visitorInstructions)
                                    <div class="text-base font-medium mt-2">
                                        {{ __('messages.errors.visitor_title') }}
                                    </div>
                                    <p class="text-sm mt-1">
                                        {{ $visitorInstructions }}
                                    </p>
                                @endif

                                @if ($ownerInstructions)
                                    <div @class([
                                        'text-base font-medium',
                                        'mt-2' => ! $visitorInstructions,
                                        'mt-4' => $visitorInstructions,
                                    ])>
                                        {{ __('messages.errors.owner_title') }}
                                    </div>
                                    <p class="text-sm mt-1">
                                        {{ $ownerInstructions }}
                                    </p>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </main>

        @filamentScripts(withCore: true)

        {{-- needed because we don't have any livewire components on error pages --}}
        @livewireScripts
    </body>
</html>
