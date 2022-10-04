@push('head')
    @stack('head-before')
    @include('layouts.partials.favicons')

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endpush
