@extends('layouts.frontend-app')
@section('title', 'Projects')

@push('meta')
    @php
        $pageDescription = 'View a selection of the various projects I have worked on and maintain.';
    @endphp

    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ $pageDescription }}">
@endpush

@section('content')
    @php
        $breadcrumbs = [
            ['url' => '#', 'display' => 'Projects']
        ];
    @endphp

    @component('frontend.components.page-header', ['breadcrumbs' => $breadcrumbs])
        @slot('title')
            Projects
        @endslot

        @slot('subtitle')
            A selection of my projects
        @endslot
    @endcomponent

    <div class="container my-3">
        <div class="row">
            <div class="col">
                <p class="lead">
                    Since most of the projects I develop are business applications, I am not able
                    to display them here. However I can show my open source projects and any public
                    websites I've developed that I'm allowed to or am comfortable showing here.
                </p>
            </div>
        </div>
    </div>

    <projects-grid inline-template
                   :projects="{{ json_encode($projects ?? []) }}"
                   :categories="{{ json_encode($categories ?? []) }}"
    >
        <div>
            <div class="container py-2" v-cloak>

                <ul v-if="categories.length"
                    class="nav nav-pills sort-source sort-source-style-3 justify-content-center"
                >
                    <li class="nav-item"
                        :class="{ active: selectedCategory === null }"
                    >
                        <a class="nav-link text-1 text-uppercase" href="#"
                           :class="{ active: selectedCategory === null }"
                           @click.prevent="selectedCategory = null"
                        >
                            Show All
                        </a>
                    </li>
                    <li v-for="(category, index) in categories" :key="`cat-${index}`"
                        class="nav-item"
                        :class="{ active: selectedCategory === category }"
                    >
                        <a href="#"
                           class="nav-link text-1 text-uppercase"
                           :class="{ active: selectedCategory === category }"
                           @click.prevent="selectedCategory = category"
                           v-text="category"
                        >
                        </a>
                    </li>
                </ul>

                <div class="mt-4 pt-2">
                    <div class="row portfolio-list sort-destination">
                        <div class="col-md-6 isotope-item"
                             v-for="(project, index) in categoryProjects"
                             :key="`project-${index}`"
                        >
                            <div class="portfolio-item">
                                <a :href="getProjectLink(project)">
                                    <span class="thumb-info thumb-info-lighten border-radius-0">
                                        <span class="thumb-info-wrapper border-radius-0">
                                            <b-img :src="getProjectImage(project)"
                                                   fluid
                                                   class="border-radius-0"
                                                   :alt="project.title"
                                            >
                                            </b-img>

                                            <span class="thumb-info-title">
                                                <span class="thumb-info-inner" v-text="project.title"></span>
                                                <span class="thumb-info-type" v-text="projectCategories(project)"></span>
                                            </span>

                                            <span class="thumb-info-action">
                                                <span class="thumb-info-action-icon bg-dark opacity-8">
                                                    <i class="mdi mdi-plus"></i>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </projects-grid>
@endsection

@push('js')
    <script src="{!! mix('js/frontend/pages/projects/index.js') !!}"></script>
@endpush
