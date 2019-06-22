@extends('layouts.frontend-app')
@section('title', 'Resume')

@push('meta')
    @php
        $pageDescription = 'Take a look a my skills, what I&#039;ve done, and my accomplishments in education.';
    @endphp

    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ $pageDescription }}">
@endpush

@section('content')
    @php
        $breadcrumbs = [
            ['url' => '#', 'display' => 'Resume']
        ];
    @endphp

    @component('frontend.components.page-header', ['breadcrumbs' => $breadcrumbs])
        @slot('title')
            Resume
        @endslot

        @slot('subtitle')
            Take a closer look at my credentials
        @endslot
    @endcomponent

    {{-- skills --}}
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <div class="heading heading-border heading-middle-border">
                    <h2 class="font-weight-normal">
                        My <strong class="font-weight-extra-bold">Skills</strong>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-6">
                <h3 class="font-weight-normal text-transform-none">Personal Skills</h3>
                <ul class="list list-icons list-icons-bold list-columns list-quaternary">
                    @foreach ($personalSkills as $skill)
                        <li>
                            <i class="mdi mdi-check"></i>
                            {{ $skill }}
                        </li>
                    @endforeach
                </ul>
                <hr style="margin-top:11px" class="height-3">
                <h3 class="font-weight-normal text-transform-none">Professional Skills</h3>
                <div class="row">
                    <div class="col-lg-6 mb-lg-0 mb-2">
                        <h5>Skilled With</h5>
                        <ul class="list list-icons list-icons-bold skills list-quaternary">
                            @foreach ($professionalSkills['skilled'] as $skill)
                                <li>
                                    <i class="mdi mdi-check"></i>
                                    {{ $skill }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <h5>Familiar With</h5>
                        <ul class="list list-icons list-icons-bold skills list-quaternary">
                            @foreach ($professionalSkills['familiar'] as $skill)
                                <li>
                                    <i class="mdi mdi-check"></i>
                                    {{ $skill }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="font-weight-normal text-transform-none">Languages</h3>

                @foreach ($languages as $language => $skillLevel)
                    <div class="mb-4">
                        <p class="mb-0">{{ $language }}</p>
                        <b-progress :value="{{ $skillLevel }}" :max="100" animated variant="quaternary"></b-progress>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- experience --}}
    <div class="container py-3">
        <div class="row mb-2">
            <div class="col">
                <div class="heading heading-border heading-middle-border">
                    <h2 class="font-weight-normal">
                        My <strong class="font-weight-extra-bold">Experience</strong>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h3 class="font-weight-normal mb-2 text-transform-none">Full-Stack Web Developer</h3>
                <p class="text-muted mb-3">
                    <b-link href="http://www.riverwoodeducational.com/" target="_blank">Riverwood Educational Services, Inc.</b-link>, Merrill, WI, May 2019 - Present
                </p>
                <p class="text-4">
                    Build and maintain an internal inventory management system utilizing PHP and the Laravel framework.
                    Primary code utilization includes HTML5, CSS3, PHP, and JavaScript, with GIT used for version control.
                </p>

                <hr class="height-3 mb-35">

                <h3 class="font-weight-normal mb-2 text-transform-none">Full-Stack Web Developer</h3>
                <p class="text-muted mb-3">
                    <b-link href="https://invizon.com" target="_blank">Invizon</b-link>, Merrill, WI, September 2015 - May 2019
                </p>
                <p class="text-4">
                    Worked on projects ranging from small, 2-man builds to 5-man sites utilizing PHP and the Laravel
                    framework. GIT was often used for version control on projects. Ensured client expectations
                    were surpassed and deadlines were met. Primary code utilization included HTML5, CSS3, PHP, and JavaScript.
                </p>

                <hr class="height-3 mb-35">

                <h3 class="font-weight-normal mb-2 text-transform-none">25U - Signal Support Systems Specialist</h3>
                <p class="text-muted mb-3">
                    <b-link href="https://www.nationalguard.com/" target="_blank">Army National Guard</b-link>, Camp Douglas, WI, September 2011 - May 2017
                </p>
                <p class="text-4">
                    Maintained radio and data distribution systems. Performed signal support functions and technical assistance
                    for computer systems. Maintained equipment, terminal devices, assigned vehicles and power generators.
                </p>
            </div>
        </div>
    </div>

    {{-- education --}}
    <div class="container py-3">
        <div class="row mb-2">
            <div class="col">
                <div class="heading heading-border heading-middle-border">
                    <h2 class="font-weight-normal">
                        My <strong class="font-weight-extra-bold">Education</strong>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h3 class="font-weight-normal mb-2 text-transform-none">Bachelor of Science in Web Technologies</h3>
                <p class="text-muted mb-3">
                    <b-link href="https://www.bellevue.edu" target="_blank">Bellevue University</b-link>, Bellevue, NE, September 2016 - March 2019
                </p>
                <hr class="height-3 mb-35">
                <h3 class="font-weight-normal mb-2 text-transform-none">Associate of Applied Science in Software Development</h3>
                <p class="text-muted mb-3">
                    <b-link href="https://www.ntc.edu" target="_blank">Northcentral Technical College</b-link>, Wausau, WI, January 2013 - May 2016
                </p>
                <h3 class="font-weight-normal my-4">Diplomas</h3>
                <ul class="list list-icons list-icons-bold list-columns list-quaternary">
                    @foreach ($ntcDiplomas as $diploma)
                        <li>
                            <i class="mdi mdi-check"></i>
                            {{ $diploma }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <section class="section bg-color-grey-scale-1 border-0 mb-0">
        <div class="container">
            <div class="row mt-4">
                <div class="col">
                    <h2 class="font-weight-normal text-6 mb-4">
                        Connect with me <strong class="font-weight-extra-bold">today</strong>
                    </h2>
                    <p>
                        Now that you've seen a brief summary of my professional experience,
                        feel free to reach out to me and start a conversation. I always enjoy
                        hearing from passionate business owners or other people in the industry.
                    </p>
                    <div class="py-4 resume-actions">
                        <b-btn href="{{ route('frontend.contact') }}" variant="dark"
                               class="font-weight-semibold btn-py-2 px-5b"
                        >
                            Contact Me
                        </b-btn>
                        <b-btn href="{{ asset('pdf/resume.pdf') }}" target="_blank" variant="dark"
                               class="font-weight-semibold btn-py-2 px-5b"
                        >
                            Download Resume
                        </b-btn>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
