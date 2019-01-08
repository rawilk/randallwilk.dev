@extends('layouts.single-project')
@section('metaDescription', 'The server setup scripts are a github repository I made that contains stack scripts I have created to help quickly deploy a LEMP stack on a Linode server, as well as some other useful bash scripts I have created.')

@section('description')
    <p>
        Having to deploy projects over and over again manually can become very
        tedious. Growing tired of this, I decided to create a repository where
        I could store some shell scripts to be executed on a new server deployment
        which would automate the entire process.
    </p>

    <p>
        Using these scripts, I was able to take a 1-2 hour job and turn it into
        a 5-minute job. The scripts I have created aren't perfect, but they work
        pretty well. In the future I do plan on improving them.
    </p>
@endsection

@section('date')
    August 1, 2018
@endsection
