@extends('layouts.page')
@section('title', 'Page Not Found')

@section('content')
    <h1>Page Not Found</h1>

    <p>Looks like you've followed a broken link or entered a URL that doesn't exist on this site.</p>

    <p>
        <a href="{{ route('home') }}">Back to site</a>
    </p>
@endsection
