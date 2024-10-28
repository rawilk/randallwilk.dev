@php
    $directory = request()->is('admin/*')
        ? 'admin'
        : 'front';
@endphp

@include("errors.{$directory}.401")
