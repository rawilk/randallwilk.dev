@extends('layouts.frontend-app')
@section('title', 'About Me')

@push('meta')
    @php
        $pageDescription = 'I’m a full-stack developer that specializes in Laravel and Vue. Web development is not only my career path, but also my passion and hobby.';
    @endphp

    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ $pageDescription }}">
@endpush

@section('content')
    @php
        $breadcrumbs = [
            ['url' => '#', 'display' => 'About Me']
        ];
    @endphp

    @component('frontend.components.page-header', ['breadcrumbs' => $breadcrumbs])
        @slot('title')
            About Me
        @endslot

        @slot('subtitle')
            Find out more about me
        @endslot
    @endcomponent

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <div class="heading heading-border heading-middle-border">
                    <h2 class="font-weight-normal">
                        My <strong class="font-weight-extra-bold">Background</strong>
                    </h2>
                </div>

                <p class="font-weight-bold">
                    I'm a full-stack web developer based in Wausau, WI. I specialize in building
                    business web applications using the Laravel framework and Vue.
                </p>

                <p>
                    At first I intended on building software applications using c# as a programming language
                    and went to school fully motivated to do just that. That all changed after I landed my
                    first job where I had to develop websites in WordPress. I won't lie, at first I didn't
                    like web development or even the PHP language. PHP grew on me though, and now I absolutely
                    love it. Web development has become a huge passion and hobby of mine.
                </p>

                <p>
                    When I started web development I primarily developed in WordPress and the JavaScript I
                    used was mostly jQuery. After about a year, I started working in Laravel and was just
                    amazed at how powerful and flexible it was. Now using Laravel as a backend and Vue as
                    a front end is my preferred platform for building web sites and applications. Of course,
                    it's not always the best tool for the job, but when possible it is what I'll use for a site.
                    In fact, this site is written using Laravel and Vue.
                </p>

                <p>
                    I'm always learning new things about programming either through school or independent study.
                    With how quickly web development changes, I firmly believe you always need to be learning
                    in order to stay relevant in the industry. I often visit <b-link href="https://laracasts.com" target="_blank">laracasts.com</b-link>
                    to learn more and to even answer questions others have on the forum there.
                </p>

                <div class="heading heading-border heading-middle-border mt-5b">
                    <h2 class="font-weight-normal">
                        What I've <strong class="font-weight-extra-bold">Done</strong>
                        & What I <strong class="font-weight-extra-bold">Can Do</strong>
                    </h2>
                </div>

                <p>
                    Feel free to take a deeper look at my skills, education, work experience
                    and what I'm able to do for you.
                </p>

                <div class="py-4 text-center">
                    <b-link href="{!! route('frontend.resume') !!}"
                            class="btn btn-dark font-weight-semibold btn-py-2 px-5b"
                    >
                        View My Resume
                    </b-link>
                </div>
            </div>
        </div>
    </div>
@endsection
