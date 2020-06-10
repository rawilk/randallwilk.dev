@extends('layouts.page')

@section('metaDescription', 'Randall Wilk is a full-stack web developer based in Wausau, WI.')

@section('content')
    <h1>About</h1>

    <img src="{{ asset('images/randall.jpg') }}" alt="Randall Wilk"
         class="sm:w-48 sm:ml-3 mb-6 sm:mb-0 sm:rounded-full sm:float-right"
    >

    <div>
        <p>
            I'm Randall Wilk, a full-stack web developer based in Wausau, WI. When it comes to programming, I'm passionate
            about PHP and my framework of choice is Laravel.
        </p>

        <p>
            Currently, I'm a developer at <a href="https://cybrixsolutions.com/">Cybrix Solutions, LLC.</a>. I use a lot of
            open source software: PHP, Ubuntu, Laravel, Composer, NPM, ... are just a few that I use everyday. Whenever I can,
            I try to learn new things about software development either through school or independent study. With how quickly
            the industry changes, I firmly believe you always need to be learning in order to stay relevant.
        </p>

        <p>
            Some of the technologies I'm most comfortable working with are PHP/Laravel, MySQL, and VueJS. I also have a lot
            of experience working with semantic, accessible, and SEO-friendly HTML, CSS, and JavaScript (including modern ES
            syntax, and build tools like Webpack). I'm currently working on learning Laravel Livewire, Tailwind CSS, and AlpineJS,
            and I hope to incorporate them into my daily workflow as well.
        </p>
    </div>

@endsection
