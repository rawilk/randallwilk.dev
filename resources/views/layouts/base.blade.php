<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.partials.analytics')
        @include('layouts.partials.meta')

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i,800,800i,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{!! mix('css/app.css') !!}">

        @if ($livewire ?? false)
            <livewire:styles />
        @endif

        @include('layouts.partials.favicons')

        @stack('head')
    </head>
    <body class="flex flex-col min-h-screen bg-gray-50">
        @include('layouts.partials.header')

        <main class="flex-grow" role="main">
            {{ $slot }}
        </main>

        @include('layouts.partials.footer')

        @if ($livewire ?? false)
            <livewire:scripts />
        @endif

        @stack('js')
    </body>
</html>
