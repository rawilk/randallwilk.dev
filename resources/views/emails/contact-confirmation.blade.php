@extends('emails.master')
@section('title', 'Contact Confirmation')

@section('content')
    <h1>Hello {{ $name }},</h1>

    <p>
        I'm sending you this email as a confirmation of you filling out my contact form on
        <a href="{{ config('app.url') }}" target="_blank" rel="noopener">{{ config('app.url') }}</a>.
        You are receiving this email because your email address ({{ $email }}) was listed as the
        contact email on the form.
    </p>

    <p style="margin-top:30px;">
        For your records, here is what you sent to me through the form:
    </p>

    <ul class="list-unstyled">
        <li><strong>Name:</strong> {{ $name }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Subject:</strong> {{ $contactSubject }}</li>
        <li><strong>Message:</strong> {{ $content }}</li>
    </ul>

    <p>
        Thank you for taking the time to contact me! I'll be in touch shortly.
    </p>

    <p>
        Thanks,
        <br>
        {{ config('randallwilk.contact_name') }}
        <br>
        <em style="font-size:90%">
            {{ config('randallwilk.contact_email') }}
            <br>
            {{ config('randallwilk.contact_phone') }}
        </em>
    </p>
@endsection