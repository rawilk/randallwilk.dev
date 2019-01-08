<!doctype html>
<html lang="{!! app()->getLocale() !!}">
    <head>
        @include('partials.basic-meta')
        <title>@yield('title') | {{ config('app.name') }}</title>
        <link rel="canonical" href="{{ getCanonicalLink($canonical ?? null, $exception ?? null) }}">
        <meta property="og:locale" content="{!! app()->getLocale() !!}">
        <meta property="og:url" content="{{ $canonical ?? request()->url() }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="@yield('title') | {{ config('app.name') }}">
        @stack('meta')
        @include('partials.favicon')
        <link rel="stylesheet" href="{!! mix('css/vendor/vendor-frontend.css') !!}">
        <link rel="stylesheet" href="{!! mix('css/frontend/theme/theme.css') !!}">
        <link rel="stylesheet" href="{!! mix('css/frontend/theme/theme-elements.css') !!}">
        <link rel="stylesheet" href="{!! mix('css/frontend/app.css') !!}">
        <link rel="stylesheet" href="{!! mix('css/frontend/theme/skins/default.css') !!}">
        @stack('styles')
    </head>
    <body>
        <div id="app" class="body">
            @include('frontend.partials.header')

            <div class="main" role="main">
                @yield('content')

                @if (! isset($showCallToAction) || $showCallToAction === true)
                    @include('frontend.partials.call-to-action')    
                @endif
                
                <back-to-top></back-to-top>
            </div>

            @include('frontend.partials.footer')
        </div>

        @include('partials.scripts.config')
        @include('partials.scripts.vendor')
        <script src="{!! mix('js/core/core.js') !!}"></script>
        @stack('js')
        <script src="{!! mix('js/frontend/app.js') !!}"></script>
        @include('partials.scripts.google-analytics')
    </body>
</html>
