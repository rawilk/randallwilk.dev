<div>
    <x-authentication-form title="{{ __('Reset Password') }}">
        <x-form wire:submit.prevent="resetPassword">
            <div class="space-y-6">

                {{-- token error --}}
                @include('livewire.auth.partials.validation-error', ['inputName' => 'token'])

                {{-- email --}}
                @if ($needsEmail)
                    <x-form-group label="{{ __('Email Address') }}" name="email">
                        <x-email wire:model.defer="email"
                                 name="email"
                                 autofocus
                                 autocomplete="email"
                                 required
                        />
                    </x-form-group>
                @else
                    @include('livewire.auth.partials.validation-error', ['inputName' => 'email'])
                @endif

                {{-- new password --}}
                <x-form-group label="{{ __('New password') }}" name="password">
                    <x-password wire:model.defer="password"
                                name="password"
                                required
                                :autofocus="$needsEmail ? null : 'autofocus'"
                    />
                </x-form-group>

                <div>
                    <x-button type="submit" variant="blue" block wire:target="resetPassword">
                        {{ __('Reset password') }}
                    </x-button>
                </div>

            </div>
        </x-form>
    </x-authentication-form>
</div>
