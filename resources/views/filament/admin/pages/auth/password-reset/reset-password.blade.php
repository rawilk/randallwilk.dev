<x-filament-panels::page.simple>
    <x-feedback.session-alert :type="App\Enums\SessionAlert::Error">
        @php($message = $component->message())

        <x-feedback.alert
            :color="App\Enums\SessionAlert::Error->color()"
            :role="null"
        >
            {{ $message }}

            <x-slot:actions>
                <x-feedback.alert-action
                    :href="filament()->getRequestPasswordResetUrl()"
                >
                    {{ __('pages/auth/reset-password.actions.request_password_reset.label') }}
                </x-feedback.alert-action>
            </x-slot:actions>
        </x-feedback.alert>
    </x-feedback.session-alert>

    <x-filament-panels::form wire:submit="resetPassword">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
</x-filament-panels::page.simple>
