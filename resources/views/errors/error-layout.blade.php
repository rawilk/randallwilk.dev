@extends('layouts.frontend-app')

@section('content')
    @component('frontend.components.page-header')
        @slot('title')
            @yield('title', 'Error')
        @endslot
    @endcomponent

    <div class="container py-5b my-5b">
        <div class="row">
            <div class="col">
                <p class="text-5 mb-4">
                    @yield('message', 'Whoops, looks like there was an error on the server')
                </p>
                <b-btn variant="dark" class="btn-modern btn-outline py-2 px-4 mt-2"
                       href="{{ route('frontend.home') }}"
                >
                    Go Home
                </b-btn>
            </div>
        </div>
    </div>
@endsection