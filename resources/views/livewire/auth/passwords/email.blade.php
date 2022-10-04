<div>
    <x-auth.authentication-form title="{{ __('auth.passwords.email.title') }}">
        <x-slot:sub-title>{{ __('auth.passwords.email.subtitle') }}</x-slot:sub-title>

        @include('layouts.partials.session-alert')

        @unless ($emailSent)
            <x-form wire:submit.prevent="sendPasswordResetLink">
                <x-form-group label="{{ __('Email address') }}" name="email">
                    <x-email
                        wire:model.defer="email"
                        name="email"
                        autocomplete="email"
                        autofocus
                        required
                    />
                </x-form-group>

                <div class="mt-6">
                    <x-button type="submit" variant="blue" block wire:target="sendPasswordResetLink">
                        {{ __('auth.passwords.email.submit_button') }}
                    </x-button>
                </div>
            </x-form>
        @endunless

        <div class="mt-4">
            @include('livewire.auth.partials.back-to-login')
        </div>
    </x-auth.authentication-form>
</div>
