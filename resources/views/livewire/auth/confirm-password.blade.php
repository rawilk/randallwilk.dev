<div>
    <x-auth.authentication-form title="{{ __('auth.passwords.confirm.title') }}">
        <x-slot:sub-title>{{ __('auth.passwords.confirm.subtitle') }}</x-slot:sub-title>

        <x-form wire:submit.prevent="confirm">
            {{-- password --}}
            <x-form-group label="{{ __('Password') }}" name="password">
                <x-password
                    wire:model.defer="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
            </x-form-group>

            <div class="mt-6">
                <x-button type="submit" variant="blue" block wire:target="confirm">
                    {{ __('auth.passwords.confirm.submit_button') }}
                </x-button>
            </div>
        </x-form>
    </x-auth.authentication-form>
</div>
