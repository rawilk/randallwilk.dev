<x-two-column-card-form title="{{ __('users.labels.profile_info_title') }}">
    <x-slot name="description">{{ __('users.labels.profile_info_sub_title') }}</x-slot>

    <x-card>
        <div class="space-y-6">

            {{-- avatar --}}
            @include('livewire.admin.users.partials.user-avatar-upload')

            {{-- name --}}
            <x-form-group name="name" label="{{ __('users.form.labels.name') }}" inline border>
                <x-input wire:model.defer="user.name"
                         name="name"
                         required
                         maxlength="100"
                         autofocus
                         max-width=" sm:max-w-xs"
                         placeholder="{{ __('users.form.labels.name_placeholder') }}"
                />
            </x-form-group>

            {{-- email --}}
            <x-form-group name="email" label="{{ __('users.form.labels.email') }}" inline border>
                <x-email wire:model.defer="user.email"
                         name="email"
                         required
                         max-width=" sm:max-w-lg"
                         placeholder="{{ __('users.form.labels.email_placeholder', ['domain' => request()->getHost()]) }}"
                />
            </x-form-group>

            {{-- timezone --}}
            <x-form-group name="timezone" label="{{ __('users.form.labels.timezone') }}" inline border>
                <x-timezone-select wire:model.defer="user.timezone"
                                   name="timezone"
                                   required
                                   max-width=" sm:max-w-lg"
                                   :only="['America', 'General']"
                                   use-custom-select
                                   fixed-position
                />
            </x-form-group>

        </div>
    </x-card>
</x-two-column-card-form>
