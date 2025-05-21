<x-mail::message
    :can-reply-to="$canReplyTo ?? false"
    :intended-email="$intendedEmail ?? null"
>
{{-- greeting --}}
@if (filled($greeting))
## {{ $greeting }}
@endif

{{-- intro lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- action button --}}
@isset($actionText)
@php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
@endphp
<x-mail::button :url="$actionUrl" :color="$color">{{ $actionText }}</x-mail::button>
@endisset

{{-- outro lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- salutation --}}
@if (filled($salutation))
{{ $salutation }}
@else
{{ App\Helpers\defaultEmailSalutation() }}
@endif

{{-- subcopy --}}
@isset($actionText)
<x-slot:subcopy>
<x-mail.subcopy
    :text="$actionText"
    :url="$actionUrl"
    :url-display="$displayableActionUrl"
/>
</x-slot:subcopy>
@endisset
</x-mail::message>
