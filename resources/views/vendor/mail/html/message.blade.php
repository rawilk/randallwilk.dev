@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ appName() }}
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
© {{ date('Y') }} {{ appName() }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
