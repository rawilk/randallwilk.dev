@extends('layouts.frontend-app')
@section('title', $post['title'])

@php
    $postImage = getPostImage($post);
@endphp

@push('meta')
    @php
        $pageDescription = $post['excerpt'] ?? '';
    @endphp

    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="article:publisher" content="{{ config('randallwilk.facebook_link') }}">
    <meta property="article:author" content="{{ config('randallwilk.facebook_link') }}">
    <meta property="og:image" content="{{ $postImage }}">
    <meta property="og:image:secure_url" content="{{ $postImage }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="https://assets-cdn.github.com/assets/gist-embed-1baaff35daab552f019ad459494450f1.css">
@endpush

@section('content')
    @component('frontend.components.post-header', [
        'breadcrumbs'  => $breadcrumbs,
        'previousPost' => $previousPost,
        'nextPost'     => $nextPost
    ])
        {{ $post['title'] }}
    @endcomponent

    <div class="blog-posts single-post">
        <article class="post post-large blog-single-post border-0 m-0 p-0">
            <div class="container py-4">
                <div class="post-image ml-0">
                    <b-img src="{{ $postImage }}" alt="{{ $post['title'] }}"
                           fluid class="img-thumbnail img-thumbnail-no-borders rounded-0"
                    >
                    </b-img>
                </div>

                <h2 class="font-weight-bold">{{ $post['title'] }}</h2>

                <div class="post-meta">
                    <span>
                        <i class="mdi mdi-calendar"></i>
                        {{ \Carbon\Carbon::parse($post['date'])->format('F d, Y') }}
                    </span>
                    <span>
                        <i class="mdi mdi-tag"></i>
                        @foreach ($post['categories'] as $category)
                            <a href="{{ getPostCategoryLink($category) }}">
                                {{ postCategoryDisplay($category) }}@if (! $loop->last), @endif
                            </a>
                        @endforeach
                    </span>
                </div>
            </div>

            @if (isOldPost($post) && canShowOldPostNotice($post))
                <div class="container py-2" v-cloak>
                    <b-alert show variant="danger" :dismissible="false">
                        <h4 class="alert-heading">
                            <i class="mdi mdi-alert-circle"></i>
                            Notice
                        </h4>
                        <p class="m-0">
                            This post is over a year old, so some information in the post
                            may be out-dated or not relevant any more.
                        </p>
                    </b-alert>
                </div>
            @endif

            @yield('post-content')

            <div class="container">
                <div class="post-block mt-5b post-share">
                    <h4 class="mb-3">Share this Post</h4>
                    @include('frontend.components.social-share', [
                        'shareTitle' => $post['title']
                    ])
                </div>

                @include('frontend.components.comments', ['disqusIdentifier' => 'post/' . $post['slug']])
            </div>
        </article>
    </div>
@endsection