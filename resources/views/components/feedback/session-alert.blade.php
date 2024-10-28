@if ($exists())
    <div {{ $attributes->merge(['role' => 'alert']) }}>
        @if ($slot->isEmpty())
            {{ $message() }}
        @else
            {{ $slot }}
        @endif
    </div>
@endif
