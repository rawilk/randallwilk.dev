@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<x-logo type="dual" />
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Unsubscribe --}}
@isset($unsubscribeUrl)
@slot('unsubscribeUrl')
{{ $unsubscribeUrl }}
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© 2015 - {{ date('Y') }} Randall Wilk. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
