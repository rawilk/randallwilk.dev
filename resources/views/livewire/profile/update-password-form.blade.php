<div>
    <x-card>
        <x-slot name="header">
            <h2 class="card__title">{{ __('users.profile.account_info.update_password_title') }}</h2>
            <p class="text-sm text-cool-gray-500">{{ __('users.profile.account_info.update_password_sub_title') }}</p>
        </x-slot>

        <x-form wire:submit.prevent="updatePassword" id="update-password-form">
            <div class="space-y-6">

                {{-- current password --}}
                <x-form-group name="current_password" label="{{ __('users.form.labels.current_password') }}" inline>
                    <x-password wire:model.defer="state.current_password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                    />

                    <x-slot name="helpText">
                        <div class="text-right">
                            <a href="{!! route('password.request') !!}"
                               class="app-link text-xs"
                            >
                                {{ __('Forgot your password?') }}
                            </a>
                        </div>
                    </x-slot>
                </x-form-group>

                {{-- new password --}}
                <x-form-group name="password" label="{{ __('users.form.labels.new_password') }}" inline border>
                    <x-password wire:model.defer="state.password"
                                name="password"
                                required
                                autocomplete="new-password"
                    />
                </x-form-group>

                {{-- password confirmation --}}
                <x-form-group name="password_confirmation" label="{{ __('users.form.labels.password_confirmation') }}" inline border>
                    <x-password wire:model.defer="state.password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                    />
                </x-form-group>

            </div>
        </x-form>

        <x-slot name="footer">
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="password.updated" />

                <x-button type="submit" variant="blue" form="update-password-form" wire:target="updatePassword">
                    <span>{{ __('labels.forms.save_button') }}</span>

                    <x-heroicon-s-check />
                </x-button>
            </div>
        </x-slot>
    </x-card>
</div>
