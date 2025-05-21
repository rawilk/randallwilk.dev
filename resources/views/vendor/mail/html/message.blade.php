@php
    use Illuminate\Support\Uri;

    $canReplyTo ??= false;
    $intendedEmail ??= null;
@endphp

<x-mail::layout>
{{-- header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
<x-logo type="dual" />
</x-mail::header>
</x-slot:header>

{{-- body --}}
{{ $slot }}

{{-- subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- footer --}}
<x-slot:footer>
<x-mail::footer>
@isset($footerBefore)
{{ $footerBefore }}

@endisset
@if (filled($intendedEmail))
{{ __('This message was mailed to [:email] by :app_name.', ['email' => e($intendedEmail), 'app_name' => Uri::of(config('app.url'))->host()]) }}

@endif
@if ($canReplyTo === false)
Please do not respond to this automated message. Emails sent from this address are not monitored.

@endif
© 2015 - {{ now()->year }} Randall Wilk. @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
