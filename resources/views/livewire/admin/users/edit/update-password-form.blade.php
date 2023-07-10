<div>
    <x-card>
        <x-slot:header>
            <h2>{{ __('users.labels.update_password_title') }}</h2>
            <p class="text-sm text-gray-500">
                {{ __('users.labels.update_password_subtitle') }}
            </p>
        </x-slot:header>

        <x-form wire:submit.prevent="updatePassword" id="update-password-form">
            <div>
                {{-- auth user's password --}}
                <x-form-group name="current_password" inline>
                    <x-password
                        wire:model.defer="state.current_password"
                        name="current_password"
                        required
                        auto-complete="current-password"
                    />

                    <x-slot:label>
                        <div class="flex items-center space-x-1">
                            <span>{{ __('Your password') }}</span>

                            <x-laravel-base::elements.tooltip-help :title="__('We require your password for an added layer of security.')" />
                        </div>
                    </x-slot:label>
                </x-form-group>

                {{-- new password --}}
                <x-form-group :label="__('New password')" name="password" inline>
                    <x-password
                        wire:model.defer="state.password"
                        name="password"
                        required
                        auto-complete="new-password"
                    />
                </x-form-group>
            </div>
        </x-form>

        <x-slot:footer>
            <div class="flex justify-end items-center space-x-4" x-data>
                <x-action-message on="password.updated" />

                <x-button
                    type="submit"
                    color="blue"
                    form="update-password-form"
                    wire:target="updatePassword"
                    right-icon="heroicon-m-check"
                >
                    {{ __('base::messages.save_button') }}
                </x-button>
            </div>
        </x-slot:footer>
    </x-card>
</div>
