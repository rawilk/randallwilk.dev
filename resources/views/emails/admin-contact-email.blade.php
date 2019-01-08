@extends('emails.master')
@section('title', $subject)

@section('content')
    <h1>Hello {{ config('randallwilk.contact_name') }},</h1>

    <p>
        <strong>{{ $name }}</strong> has just sent you a message from your contact form on
        <a href="{{ config('app.url') }}" target="_blank" rel="noopener">{{ config('app.url') }}</a>.
    </p>

    <p style="margin-top:30px;">
        Here is the information about the inquiry:
    </p>

    <ul class="list-unstyled">
        <li><strong>Name:</strong> {{ $name }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Subject:</strong> {{ $contactSubject }}</li>
        <li><strong>Message:</strong> {{ $content }}</li>
    </ul>
@endsection