@php use Illuminate\View\ComponentAttributeBag; @endphp
@props([
    'headStart' => null,
    'head' => null,
    'title' => null,
    'htmlAttributes' => null,
])

@php
    $htmlAttributes ??= new Illuminate\View\ComponentAttributeBag;
@endphp

<!DOCTYPE html>
<html
    {{
        $htmlAttributes
            ->merge([
                'lang' => str_replace('_', '-', app()->getLocale()),
                'dir' => __('filament-panels::layout.direction') ?? 'ltr',
            ], escape: false)
            ->class([
                'min-h-screen',
                'bg-white',
            ])
    }}
>
    <head>
        {{ $headStart }}
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />

        @if (filled($title))
            <title>{{ $title }}</title>
        @endif

        @stack('styles')
        {{ $head }}
    </head>

    <body {{ $attributes->class(['min-h-screen']) }}>
        {{ $slot }}

        @stack('js')
    </body>
</html>
