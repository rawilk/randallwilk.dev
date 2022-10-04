<div>
    <x-card>
        <x-slot:header>
            <h2>{{ __('users.profile.authentication.update_password_title') }}</h2>
            <p class="text-sm text-gray-500">{{ __('users.profile.authentication.update_password_subtitle') }}</p>
        </x-slot:header>

        <x-form wire:submit.prevent="updatePassword" id="update-password-form">
            <div>
                {{-- current password --}}
                <x-form-group label="{{ __('users.profile.form.labels.current_password') }}" name="current_password" inline>
                    <x-password
                        wire:model.defer="state.current_password"
                        name="current_password"
                        required
                        autocomplete="current-password"
                    />

                    @if (\Rawilk\LaravelBase\Features::resetPasswords())
                        <x-slot:helpText>
                            <div class="text-right">
                                <x-link href="{!! route('password.request') !!}" class="link-underline link-gray text-xs">
                                    {{ __('auth.login.forgot_password_link') }}
                                </x-link>
                            </div>
                        </x-slot:helpText>
                    @endif
                </x-form-group>

                {{-- new password --}}
                <x-form-group label="{{ __('users.profile.form.labels.new_password') }}" name="password" inline>
                    <x-password
                        wire:model.defer="state.password"
                        name="password"
                        required
                        autocomplete="new-password"
                    />
                </x-form-group>

                {{-- password confirmation --}}
                <x-form-group label="{{ __('users.profile.form.labels.confirm_password') }}" name="password_confirmation" inline>
                    <x-password
                        wire:model.defer="state.password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </x-form-group>
            </div>
        </x-form>
        
        <x-slot:footer>
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="saved" />

                <x-button type="submit" variant="blue" form="update-password-form" wire:target="updatePassword">
                    <span>{{ __('base::messages.save_button') }}</span>
                </x-button>
            </div>
        </x-slot:footer>
    </x-card>
</div>
