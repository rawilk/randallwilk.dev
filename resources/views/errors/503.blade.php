<!doctype html>
<html lang="{!! app()->getLocale() !!}">
    <head>
        @include('partials.basic-meta')
        <title>Service Unavailable</title>
        <link rel="canonical" href="{{ getCanonicalLink($canonical ?? null, $exception ?? null) }}">
        @include('partials.favicon')
        <link rel="stylesheet" href="{!! mix('css/vendor/vendor-frontend.css') !!}">
        <link rel="stylesheet" href="{!! mix('css/frontend/theme/theme.css') !!}">
        <link rel="stylesheet" href="{!! mix('css/frontend/theme/theme-elements.css') !!}">
        <link rel="stylesheet" href="{!! mix('css/frontend/app.css') !!}">
        <link rel="stylesheet" href="{!! mix('css/frontend/theme/skins/default.css') !!}">
    </head>
    <body>
        <div id="app" class="body">
            <div class="main" role="main">
                <div class="container py-5b">
                    <div class="row mt-5b">
                        <div class="col text-center">
                            <div class="logo">
                                <b-img src="{{ appLogo() }}" alt="{{ config('app.name') }}" fluid></b-img>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <hr class="sold my-5b">
                        </div>
                    </div>
                    <div class="row py-4">
                        <div class="col text-center">
                            <h1 class="font-weight-extra-bold text-10 mb-4">Maintenance Mode</h1>
                            <p class="text-4">
                                Sorry, the website is undergoing some scheduled maintenance.
                                <br>
                                Please check back later.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <hr class="sold my-5b">
                        </div>
                    </div>
                </div>

                <back-to-top></back-to-top>
            </div>
        </div>

        @include('partials.scripts.config')
        @include('partials.scripts.vendor')
        <script src="{!! mix('js/core/core.js') !!}"></script>
        @stack('js')
        <script src="{!! mix('js/frontend/app.js') !!}"></script>
        @include('partials.scripts.google-analytics')
    </body>
</html>

{{--@extends('errors.error-layout')--}}
{{--@section('title', 'Service Unavailable')--}}

{{--@section('message')--}}
    {{--Sorry, I am performing some maintenance. Please check back soon.--}}
{{--@endsection--}}
