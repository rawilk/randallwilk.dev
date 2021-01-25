<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{ $headTop ?? '' }}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title() }}</title>

        {{ $head ?? '' }}
    </head>
    <body {{ $attributes }}>
        {{ $slot }}
    </body>
</html>
