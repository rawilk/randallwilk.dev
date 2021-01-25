<div>
    <x-card :rounded-on-mobile="false">
        <x-slot name="header">
            <h2 class="card__title">{{ __('users.labels.account_info_title') }}</h2>
            <p class="text-sm text-cool-gray-500">
                {{ __('users.labels.account_info_sub_title') }}
            </p>
        </x-slot>

        <div class="space-y-6">

            {{-- is admin --}}
            <x-form-group name="is_admin" label="{{ __('users.form.labels.is_admin') }}" inline is-checkbox-group>
                <x-checkbox wire:model.defer="state.is_admin"
                            name="is_admin"
                            :disabled="auth()->user()->is($user)"
                />

                <x-slot name="helpText">{{ __('users.form.labels.is_admin_help') }}</x-slot>
            </x-form-group>

        </div>

        <x-slot name="footer">
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="account.updated" />

                <x-confirms-password wire:then="updateAccount">
                    <x-button wire:target="updateAccount,startConfirmingPassword" variant="primary">
                        <span>{{ __('labels.forms.save_button') }}</span>

                        <x-heroicon-s-check />
                    </x-button>
                </x-confirms-password>
            </div>
        </x-slot>
    </x-card>
</div>
