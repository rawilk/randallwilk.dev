@extends('layouts.frontend-app')
@section('title', 'Full-Stack Web Developer')

@push('meta')
    <meta name="description" content="{{ config('randallwilk.default_page_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{ config('randallwilk.default_page_description') }}">
@endpush

@section('content')
    <section class="section border-0 pt-0 pt-md-5 m-0 index-first-section">
        <div class="container">
            <div class="row text-center py-3">
                <div class="col">
                    <h1 class="text-8 mb-2">
                        Hello, I'm <span class="text-primary">Randall Wilk</span>.
                    </h1>
                    <h1 class="text-8 mb-5b">
                        I'm a full-stack web developer.
                    </h1>
                    <b-btn variant="dark" class="btn-modern btn-outline py-2 px-4"
                           href="{{ route('frontend.projects') }}"
                    >
                        View My Work
                    </b-btn>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5b my-4">
        <div class="row text-left text-lg-center py-3">
            <div class="col-md-10 mx-md-auto">
                <h1 class="font-weight-bold text-8 mb-3">
                    I develop beautiful and responsive websites
                </h1>
                <p class="lead mb-0">
                    I can take your project from start to finish. I'm more than capable
                    of writing the back-end functions to make your application work, as well
                    as implementing those functions on the front-end of the site and designing
                    it to look amazing. You will find I have all the skills necessary to
                    build your website or web application.
                </p>
            </div>
        </div>
    </div>

    <section class="section section-height-3 bg-primary border-0 m-0">
        <div class="container">
            <div class="row pb-5b">
                <div class="col">
                    <h2 class="font-weight-normal text-color-light text-6 mb-4">
                        <strong class="font-weight-extra-bold">Featured</strong> Projects
                    </h2>
                    <div class="pricing-table mb-4">
                        @php
                            $featuredProjects = getFeaturedProjects();
                            $featuredCount = count($featuredProjects);
                        @endphp

                        @foreach ($featuredProjects as $project)
                            <div class="col-md-6 col-lg-{{ min(6, max(3, (12 / $featuredCount))) }}">
                                <div class="plan">
                                    <div class="plan-header">
                                        <h3>{{ $project['title'] }}</h3>
                                    </div>
                                    <div class="plan-price p-0 border-bottom">
                                        <b-img src="{{ getProjectImage($project) }}" alt="{{ $project['title'] }}"
                                               fluid
                                        >
                                        </b-img>
                                    </div>
                                    <div class="plan-features mt-3">
                                        <p class="m-0">{!! $project['excerpt'] !!}</p>
                                    </div>
                                    <div class="plan-footer">
                                        <b-btn variant="dark" class="btn-modern btn-outline py-2 px-4"
                                               href="{{ route('frontend.projects.view', ['project' => $project['slug']]) }}"
                                        >
                                            More Info
                                        </b-btn>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-color-grey-scale-1 border-0 pt-0 pt-md-5 m-0">
        <div class="container">
            <div class="row align-items-center justify-content-center pb-4 pb-lg-0">
                <div class="col-lg-6 order-2 order-lg-1 pr-5 pt-4 pt-lg-0 mt-md-5 mt-lg-0">
                    <h2 class="font-weight-normal text-6 mb-3">
                        <strong class="font-weight-extra-bold">Who</strong>
                        I am
                    </h2>
                    <p class="lead">
                        I'm a full-stack developer that specializes in Laravel and Vue. Web development
                        is not only my career path, but also my passion and hobby. I'm very detail oriented
                        and strive to make every project I work on to be unique and beautiful on all screen
                        sizes and devices.
                    </p>
                    <b-link href="{!! route('frontend.about') !!}" class="btn btn-dark font-weight-semibold btn-py-2 px-5b">
                        Learn More
                    </b-link>
                </div>
                <div class="col-9 col-lg-6 order-1 order-lg-2 scale-6 pb-5 pb-lg-0 mt-0 mt-md-4 mb-5 mb-lg-0 code-computer">
                    <b-img fluid
                           lazy
                           src="{{ asset('images/computer-with-code.png') }}"
                           alt="Computer with code"
                           v-cloak
                    >
                    </b-img>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5b my-5b">
        <div class="featured-boxes featured-boxes-style-3 featured-boxes-flat py-4">
            <div class="row">

                <div class="col-lg-4 col-sm-6">
                    @component('frontend.components.featured-box')
                        @slot('icon', 'mdi mdi-code-braces')
                        @slot('title', 'Custom Programming')

                        <p class="mb-0">
                            My expertise in HTML5, CSS3, PHP, Laravel and Vue
                            guarantees web site designs that are optimized
                            for all browsers and major mobile devices
                            including iPhone and iPad.
                        </p>
                    @endcomponent
                </div>

                <div class="col-lg-4 col-sm-6">
                    @component('frontend.components.featured-box')
                        @slot('icon', 'mdi mdi-desktop-mac')
                        @slot('title', 'Web Design')

                        <p class="mb-0">
                            Parallax, responsive, creative and everything else on the web,
                            I have it covered.
                        </p>
                    @endcomponent
                </div>

                <div class="col-lg-4 col-sm-6">
                    @component('frontend.components.featured-box')
                        @slot('icon', 'mdi mdi-image-multiple')
                        @slot('title', 'Content Management')

                        <p class="mb-0">
                            I have expertise in developing custom solutions for Laravel and WordPress, as
                            well as developing custom plugins for WordPress.
                        </p>
                    @endcomponent
                </div>

            </div>
        </div>
    </div>

    <section class="section bg-color-grey-scale-1 border-0 mb-0">
        <div class="container">
            <div class="row mt-4">
                <div class="col">
                    <h2 class="font-weight-normal text-6 mb-4">
                        <strong class="font-weight-extra-bold">Latest</strong>
                        Posts
                    </h2>

                    <div class="row recent-posts">
                        @foreach (latestPosts() as $post)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <article class="post pb-5b">
                                    <div class="post-image">
                                        <a href="{{ getPostLink($post) }}">
                                            <b-img src="{{ getPostImage($post) }}"
                                                   alt="{{ $post['title'] }}"
                                                   fluid
                                                   class="img-thumbnail img-thumbnail-no-borders rounded-0"
                                            >
                                            </b-img>
                                        </a>
                                    </div>
                                    <div class="post-content mt-3">
                                        <h4 class="post-title-link">
                                            <a href="{{ getPostLink($post) }}">
                                                {{ $post['title'] }}
                                            </a>
                                        </h4>
                                        <div class="post-meta mb-3">
                                            <span>
                                                <i class="mdi mdi-calendar"></i>
                                                {{ \Carbon\Carbon::parse($post['date'])->format('F d, Y') }}
                                            </span>
                                        </div>
                                        <p class="mb-1">
                                            {{ $post['excerpt'] }}
                                        </p>
                                        <a href="{{ getPostLink($post) }}"
                                           class="read-more text-color-dark font-weight-bold text-2"
                                        >
                                            Read more
                                        </a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
