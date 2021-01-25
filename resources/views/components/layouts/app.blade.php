<x-html :title="$title ?? ''">
    <x-slot name="headTop">
        @stack('head-top')
    </x-slot>

    <x-slot name="head">
        <livewire:styles />

        @stack('head-before')
        @include('components.layouts.partials.favicons')

        @stack('head')
    </x-slot>

    {{ $slot }}

    <livewire:scripts />
    @fcJavaScript
    <script src="{!! mix('js/app.js') !!}"></script>
    @stack('js')
</x-html>
