@extends('layouts.frontend-app')
@section('title', 'Page Not Found')

@section('content')
    @component('frontend.components.page-header')
        @slot('title')
            Page Not Found
        @endslot
    @endcomponent

    <div class="container py-5b">
        <div class="row justify-content-center pb-50">
            <b-col md="7">
                <h1 class="font-weight-bold text-10 mb-3">Oops!</h1>
                <p class="text-4 pr-0 pr-lg-4">
                    The page you are looking for can't be found. It looks like nothing was found
                    at this location.
                </p>
            </b-col>
            <b-col md="5">
                <h4>Here are some useful links</h4>
                <ul class="nav nav-list flex-column">
                    <li class="nav-item">
                        <b-link href="{{ route('frontend.home') }}" class="nav-link">
                            Home
                        </b-link>
                    </li>
                    <li class="nav-item">
                        <b-link href="{{ route('frontend.about') }}" class="nav-link">
                            About
                        </b-link>
                    </li>
                    <li class="nav-item">
                        <b-link href="{{ route('frontend.projects') }}" class="nav-link">
                            Projects
                        </b-link>
                    </li>
                    <li class="nav-item">
                        <b-link href="{{ route('frontend.resume') }}" class="nav-link">
                            Resume
                        </b-link>
                    </li>
                    <li class="nav-item">
                        <b-link href="{{ route('frontend.blog') }}" class="nav-link">
                            Blog
                        </b-link>
                    </li>
                    <li class="nav-item">
                        <b-link href="{{ route('frontend.contact') }}" class="nav-link">
                            Contact
                        </b-link>
                    </li>
                    <li class="nav-item">
                        <b-link href="{{ route('frontend.privacy') }}" class="nav-link">
                            Privacy Policy
                        </b-link>
                    </li>
                    <li class="nav-item">
                        <b-link href="{{ route('frontend.terms') }}" class="nav-link">
                            Terms & Conditions
                        </b-link>
                    </li>
                </ul>
            </b-col>
        </div>
    </div>
@endsection