@extends('layouts.page')
@section('title', 'Contact')

@section('metaDescription', 'Contact Randall Wilk to discuss a consultancy arrangement, ask a question, or just to say hello!')

@section('content')
    <h1>Contact</h1>

    <p>
        If you would like to discuss a consulting or speaking arrangement with me, you may email me
        at <a href="mailto:{{ config('site.contact.email') }}">{{ config('site.contact.email') }}</a> and I will do my best
        to respond within 24 hours.
    </p>

    <p>
        I also have profiles on various social networking sites:
    </p>

    <ul>
        @foreach (config('site.contact.social') as $key => $value)
            <li>{{ $key }}: <a href="{{ $value }}">{{ $value }}</a></li>
        @endforeach
    </ul>
@endsection
