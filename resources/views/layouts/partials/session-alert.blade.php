@foreach (['success', 'warning', 'danger'] as $type)
    <x-session-alert :type="$type">
        @php($message = $component->message())

        <x-alert :type="$type">
            <p class="alert__text">{!! $message !!}</p>
        </x-alert>
    </x-session-alert>
@endforeach
