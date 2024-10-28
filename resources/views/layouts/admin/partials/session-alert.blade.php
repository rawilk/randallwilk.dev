@foreach (App\Enums\SessionAlert::cases() as $case)
    <x-feedback.session-alert :type="$case" class="mt-4">
        @php($message = $component->message())

        <x-feedback.alert
            :color="$case->color()"
            remove-parent-on-dismiss
            dismiss
            :role="null"
        >
            {{ str(e($message))->markdown()->toHtmlString() }}
        </x-feedback.alert>
    </x-feedback.session-alert>
@endforeach
