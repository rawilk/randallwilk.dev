<div>
    <x-card>
        <x-slot name="header">
            <h2 class="card__title">
                {{ __('users.labels.profile_info_title') }}
            </h2>
            <p class="text-sm text-cool-gray-500">
                {{ __('users.labels.profile_info_update_sub_title') }}
            </p>
        </x-slot>

        <x-form wire:submit.prevent="save" id="user-details-form">
            <div class="space-y-6">

                {{-- avatar --}}
                @include('livewire.admin.users.partials.user-avatar-upload')

                {{-- name --}}
                <x-form-group name="name" label="{{ __('users.form.labels.name') }}" inline border>
                    <x-input wire:model.defer="state.name"
                             name="name"
                             required
                             maxlength="100"
                             max-width=" sm:max-w-xs"
                             placeholder="{{ __('users.form.labels.name_placeholder') }}"
                    />
                </x-form-group>

                {{-- email --}}
                <x-form-group name="email" label="{{ __('users.form.labels.email') }}" inline border>
                    <x-email wire:model.defer="state.email"
                             name="email"
                             required
                             max-width=" sm:max-w-lg"
                             placeholder="{{ __('users.form.labels.email_placeholder') }}"
                    />
                </x-form-group>

                {{-- timezone --}}
                <x-form-group name="timezone" label="{{ __('users.form.labels.timezone') }}" inline border>
                    <x-timezone-select wire:model.defer="state.timezone"
                                       name="timezone"
                                       required
                                       max-width=" sm:max-w-lg"
                                       :only="['America', 'General']"
                                       use-custom-select
                                       fixed-position
                    />
                </x-form-group>

            </div>
        </x-form>

        <x-slot name="footer">
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="profile.updated" />

                <x-button type="submit" form="user-details-form" wire:target="save" variant="blue">
                    <span>{{ __('labels.forms.save_button') }}</span>

                    <x-heroicon-s-check />
                </x-button>
            </div>
        </x-slot>
    </x-card>

    <x-update-title-script function="userInfo" action="profile.updated">
        const newTitle = @this.state['name'];

        updateBreadcrumb(newTitle);
    </x-update-title-script>
</div>
