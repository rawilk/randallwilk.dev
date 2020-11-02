<div>
    <x-authentication-form :title="__('Sign in to your account')">
        <x-form wire:submit.prevent="login">
            <div class="space-y-6">

                <x-form-group :label="__('Email address')" name="email">
                    <x-email wire:model.defer="email"
                             name="email"
                             autofocus
                             required
                    />
                </x-form-group>

                <x-form-group :label="__('Password')" name="password">
                    <x-password wire:model.defer="password"
                                name="password"
                                required
                    />
                </x-form-group>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <x-checkbox wire:model.defer="remember" name="remember">
                            {{ __('Remember me') }}
                        </x-checkbox>
                    </div>

                    <div class="text-sm leading-5">
                        <a href="{{ route('password.request') }}" class="app-link">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                </div>

                <div>
                    <x-button type="submit" block wire:target="login">
                        {{ __('Sign in') }}
                    </x-button>
                </div>

            </div>
        </x-form>
    </x-authentication-form>
</div>
