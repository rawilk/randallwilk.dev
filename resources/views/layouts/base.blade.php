<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
    <head>
        @include('includes.google-analytics')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@hasSection('title') @yield('title') | @endif {{ config('app.name') }}</title>
        @include('includes.meta')
        @stack('meta')

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="home" href="{{ config('app.url') }}">

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i,800,800i,900" rel="stylesheet">
        <link rel="stylesheet" href="{!! mix('css/app.css') !!}">
        @stack('css')
    </head>
    <body class="flex flex-col justify-between min-h-screen font-sans leading-normal text-gray-800 bg-gray-100">
        @yield('content')

        <footer class="py-4 text-sm text-center bg-white" role="contentinfo">
            <ul class="flex flex-col justify-center list-none md:flex-row mb-0">
                <li class="md:mr-2">
                    &copy; {{ date('Y') }}. All Rights Reserved.
                </li>

                <li>
                    Built with <a href="https://laravel.com" title="Laravel, the PHP Framework for Web Artisans">Laravel</a>
                    and <a href="https://tailwindcss.com" title="Tailwind CSS, a utility-first CSS framework">Tailwind CSS</a>.
                </li>
            </ul>

            <ul class="flex flex-col justify-center list-none md:flex-row mt-1">
                <li>
                    Proudly hosted with <a href="https://forge.laravel.com" title="Provision and deploy unlimited PHP applications on DigitalOcean, Linode, Vultr, Amazon and more.">Laravel Forge</a>
                    and <a href="https://m.do.co/c/0f79a60f0243" title="DigitalOcean - The developer cloud">DigitalOcean</a>.
                </li>
            </ul>
        </footer>

        <script src="{!! mix('js/app.js') !!}"></script>
        @stack('js')
    </body>
</html>
