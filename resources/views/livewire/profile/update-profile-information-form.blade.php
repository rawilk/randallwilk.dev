<div>
    <x-card>
        <x-slot:header>
            <h2>{{ __('users.profile.account.profile_info_title') }}</h2>
            <p class="text-sm text-gray-500">{{ __('users.profile.account.profile_info_subtitle') }}</p>
        </x-slot:header>

        <x-form wire:submit.prevent="updateProfileInformation" id="update-profile-information-form">
            {{-- avatar --}}
            @includeWhen(\Rawilk\LaravelBase\Features::managesAvatars(), 'livewire.admin.users.partials.avatar-upload')

            {{-- name --}}
            <x-form-group label="{{ __('users.form.labels.name') }}" name="name" inline>
                <x-input
                    wire:model.defer="state.name"
                    name="name"
                    required
                    autocomplete="name"
                    maxlength="255"
                    max-width=" sm:max-w-sm"
                />
            </x-form-group>

            {{-- email --}}
            <x-form-group label="{{ __('users.form.labels.email') }}" name="email" inline>
                <x-email
                    wire:model.defer="state.email"
                    name="email"
                    required
                    maxlength="255"
                />
            </x-form-group>

            {{-- timezone --}}
            <x-form-group label="{{ __('users.form.labels.timezone') }}" name="timezone" inline>
                <x-timezone-select
                    wire:model.defer="state.timezone"
                    name="timezone"
                    required
                    use-custom-select
                    :only="timezoneSubsets()"
                />
            </x-form-group>
        </x-form>

        <x-slot:footer>
            <div class="flex items-center justify-end space-x-4">
                <x-action-message on="profile.updated" />

                <x-button type="submit" variant="blue" form="update-profile-information-form" wire:target="updateProfileInformation">
                    <span>{{ __('base::messages.save_button') }}</span>
                </x-button>
            </div>
        </x-slot:footer>
    </x-card>
</div>
