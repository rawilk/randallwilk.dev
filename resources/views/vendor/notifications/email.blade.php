<div style="display:none;">@formatter:off</div>

@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
## {{ $greeting }}
@elseif ($greeting !== false)
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@elseif ($salutation !== false)
@lang('Regards'),<br>
Randall Wilk
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
{!! __("If you're having trouble clicking the \":actionText\" button, copy and paste the URL below into your browser:", ['actionText' => $actionText]) !!}

<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
