<div>
    <x-authentication-form :title="__('Reset password')">

        <x-slot name="subTitle">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </x-slot>

        @include('layouts.partials.session-alert')

        @if (! $emailSent)
            <x-form wire:submit.prevent="sendPasswordResetLink">
                <div class="space-y-6">

                    <x-form-group :label="__('Email address')" name="email">
                        <x-email wire:model.defer="email"
                                 name="email"
                                 autocomplete="email"
                                 autofocus
                                 required
                        />
                    </x-form-group>

                    <div>
                        <x-button variant="blue" type="submit" block wire:target="sendPasswordResetLink">
                            {{ __('Send password reset link') }}
                        </x-button>
                    </div>

                    @include('livewire.auth.partials.back-to-login')

                </div>
            </x-form>
        @endif
    </x-authentication-form>
</div>
