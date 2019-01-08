@extends('layouts.frontend-app')
@section('title', 'Web Development Blog')

@push('meta')
    @php
        $pageDescription = 'Read some articles on web development.';
    @endphp

    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ $pageDescription }}">
@endpush

@section('content')
    @php
        $breadcrumbs = [
            ['url' => '#', 'display' => 'Web Development Blog']
        ];
    @endphp

    @component('frontend.components.page-header', ['breadcrumbs' => $breadcrumbs])
        @slot('title')
            Web Development Blog
        @endslot
    @endcomponent

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <div class="heading heading-border heading-middle-border">
                    <h2 class="font-weight-normal">
                        Latest <strong class="font-weight-extra-bold">Posts</strong>
                    </h2>
                </div>
            </div>
        </div>

        @include('frontend.partials.posts')
    </div>
@endsection
