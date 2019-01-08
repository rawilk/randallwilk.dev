@extends('layouts.frontend-app')
@section('title', $project['title'])

@php
    $projectImage = getProjectImage($project);
@endphp

@push('meta')
    <meta name="description" content="@yield('metaDescription')">
    <meta property="og:type" content="article">
    <meta property="og:description" content="@yield('metaDescription')">
    <meta property="og:image" content="{{ $projectImage }}">
    <meta property="og:image:secure_url" content="{{ $projectImage }}">
    <meta property="article:publisher" content="{{ config('randallwilk.facebook_link') }}">
    <meta property="article:author" content="{{ config('randallwilk.facebook_link') }}">
@endpush

@section('content')
    @component('frontend.components.project-header', [
        'breadcrumbs'     => $breadcrumbs,
        'nextProject'     => $nextProject,
        'previousProject' => $previousProject
    ])
        {{ $project['title'] }}
    @endcomponent

    <div class="container pt-2 pb-4 single-project">
        <div class="row pb-4 mb-2">
            <div class="col-md-6 mb-4 mb-md-0">
                <div>
                    <div class="img-thumbnail border-0 border-radius-0 p-0 d-block">
                        <b-img src="{{ $projectImage }}" alt="{{ $project['title'] }}"
                               fluid class="border-radius-0"
                        >
                        </b-img>
                    </div>
                </div>
                <hr class="solid my-5b">
                <div>
                    <strong class="text-uppercase text-1 mr-3 text-dark float-left position-relative top-2">Share</strong>
                    @include('frontend.components.social-share', [
                        'shareTitle' => 'Check out ' . $project['title']
                    ])
                </div>
            </div>

            <div class="col-md-6">
                <div class="overflow-hidden">
                    <h2 class="text-color-dark font-weight-normal text-4 mb-0">
                        Project
                        <strong class="font-weight-extra-bold">Description</strong>
                    </h2>
                </div>
                <div>
                    @yield('description')
                </div>
                <div class="overflow-hidden mt-4">
                    <h2 class="text-color-dark font-weight-normal text-4 mb-0">
                        Project
                        <strong class="font-weight-extra-bold">Details</strong>
                    </h2>
                </div>
                <ul class="list list-icons list-primary list-borders text-2">

                    @if (View::hasSection('version'))
                        <li>
                            <i class="mdi mdi-menu-right text-5 left-2"></i>
                            <strong class="text-color-primary">Version:</strong>
                            @yield('version')
                        </li>
                    @endif

                    @if (View::hasSection('date'))
                        <li>
                            <i class="mdi mdi-menu-right text-5 left-2"></i>
                            <strong class="text-color-primary">Date:</strong>
                            @yield('date')
                        </li>
                    @endif

                    <li>
                        <i class="mdi mdi-menu-right text-5 left-2"></i>
                        <strong class="text-color-primary">Skills:</strong>
                        @foreach ($project['skills'] as $skill)
                            <b-badge variant="dark" pill class="badge-sm px-2 py-1 ml-1">
                                {{ $skill }}
                            </b-badge>
                        @endforeach
                    </li>

                    <li>
                        <i class="mdi mdi-menu-right text-5 left-2"></i>
                        <strong class="text-color-primary">Project URL:</strong>
                        <b-link href="{{ $project['uri'] }}" target="_blank" class="text-dark">{{ $project['uri'] }}</b-link>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    {{--@component('frontend.components.project-body', ['project' => $project])--}}
        {{--<p>--}}
            {{--vue-context is a simple yet flexible context menu for Vue. It is styled for the standard--}}
            {{--<code>ul</code> tag, but any menu template can be used. I made this package pretty lightweight--}}
            {{--and its only dependency is Vue.--}}
        {{--</p>--}}

        {{--<p>--}}
            {{--The menu can be configured to disappear on scroll and when clicked on. Any clicks outside the--}}
            {{--menu will always close it.--}}
        {{--</p>--}}

        {{--@slot('version')--}}
            {{--3.4.0--}}
        {{--@endslot--}}

        {{--@slot('date')--}}
            {{--August 18, 2017--}}
        {{--@endslot--}}
    {{--@endcomponent--}}
@endsection