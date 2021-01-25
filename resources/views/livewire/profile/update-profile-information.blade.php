<div>
    <x-card :rounded-on-mobile="false">
        <x-slot name="header">
            <h2 class="card__title">{{ __('users.profile.account_info.profile_info_title') }}</h2>
            <p class="text-sm text-cool-gray-500">{{ __('users.profile.account_info.profile_info_sub_title') }}</p>
        </x-slot>

        <x-form wire:submit.prevent="updateProfileInformation" id="update-profile-information-form">
            <div class="space-y-6">

                {{-- avatar --}}
                @include('livewire.admin.users.partials.user-avatar-upload')

                {{-- name --}}
                <x-form-group name="name" label="{{ __('users.form.labels.name') }}" inline border>
                    <x-input wire:model.defer="state.name"
                             name="name"
                             required
                             autocomplete="name"
                             maxlength="100"
                             max-width=" sm:max-w-xs"
                    />
                </x-form-group>

                {{-- email --}}
                <x-form-group name="email" label="{{ __('users.form.labels.email') }}" inline border>
                    <x-email wire:model.defer="state.email"
                             name="email"
                             required
                             max-width=" sm:max-w-lg"
                    />
                </x-form-group>

                {{-- timezone --}}
                <x-form-group name="timezone" label="{{ __('users.form.labels.timezone') }}" inline border>
                    <x-timezone-select wire:model.defer="state.timezone"
                                       name="timezone"
                                       required
                                       max-width=" sm:max-w-lg"
                                       use-custom-select
                                       fixed-position
                                       :only="['America', 'General']"
                    />
                </x-form-group>

            </div>
        </x-form>

        <x-slot name="footer">
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="profile.updated" />

                <x-button type="submit" variant="primary" form="update-profile-information-form" wire:target="updateProfileInformation">
                    <span>{{ __('labels.forms.save_button') }}</span>

                    <x-heroicon-s-check />
                </x-button>
            </div>
        </x-slot>
    </x-card>
</div>
