@extends('layouts.page')
@section('title', 'Projects')

@section('metaDescription', 'Randall Wilk has various projects in the open source community.')

@section('content')
    <h1>My Projects</h1>

    <h2>Open Source</h2>
    <ul>
        @foreach (config('site.projects.openSource') as $project)
            <li><a href="{{ $project['url'] }}">{{ $project['name'] }}</a></li>
        @endforeach
    </ul>
@endsection
