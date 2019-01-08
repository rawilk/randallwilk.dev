@extends('layouts.frontend-app')
@section('title', $category)

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
            ['url' => route('frontend.blog'), 'display' => 'Web Development Blog'],
            ['url' => '#', 'display' => $category]
        ];
    @endphp

    @component('frontend.components.page-header', ['breadcrumbs' => $breadcrumbs])
        @slot('title')
            {{ $category }}
        @endslot
    @endcomponent

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <div class="heading heading-border heading-middle-border">
                    <h2 class="font-weight-normal">
                        {{ $category }} <strong class="font-weight-extra-bold">Posts</strong>
                    </h2>
                </div>
            </div>
        </div>

        @include('frontend.partials.posts')
    </div>
@endsection
