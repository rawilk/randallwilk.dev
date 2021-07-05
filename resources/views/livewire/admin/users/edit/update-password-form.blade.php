<div>
    <x-card>
        <x-slot name="header">
            <h2 class="card__title">{{ __('users.labels.update_password_title') }}</h2>
            <p class="text-sm text-cool-gray-500">
                {{ __('users.labels.update_password_sub_title') }}
            </p>
        </x-slot>

        <x-form wire:submit.prevent="updatePassword" id="update-password-form">
            <div class="space-y-6">

                {{-- current password (for auth user) --}}
                <x-form-group name="current_password" inline>
                    <x-password wire:model.defer="state.current_password"
                                name="current_password"
                                required
                                auto-complete="current-password"
                    />

                    <x-slot name="label">
                        {{ __('users.form.labels.your_password') }}
                        <x-tooltip-help title="{{ __('users.form.labels.your_password_help') }}" />
                    </x-slot>
                </x-form-group>

                {{-- new password --}}
                <x-form-group name="password" label="{{ __('New password') }}" inline border>
                    <x-password wire:model.defer="state.password"
                               name="password"
                               rquired
                               auto-complete="new-password"
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
