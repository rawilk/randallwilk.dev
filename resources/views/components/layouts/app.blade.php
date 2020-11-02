<x-html :title="$title ?? ''">
    <x-slot name="headTop">
        @stack('head-top')
    </x-slot>

    <x-slot name="head">
        <livewire:styles />

        @stack('head-before')
        @include('components.layouts.partials.favicons')
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i,800,800i,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Fira+Mono:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{!! mix('css/app.css') !!}">
        @stack('head')
    </x-slot>

    {{ $slot }}

    <livewire:scripts />
    @fcJavaScript
    <script src="{!! mix('js/app.js') !!}"></script>
    @stack('js')
</x-html>
