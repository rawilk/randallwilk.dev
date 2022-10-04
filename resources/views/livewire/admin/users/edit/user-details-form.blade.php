<div>
    <x-card>
        <x-slot:header>
            <h2>{{ __('users.labels.profile_info_title') }}</h2>
            <p class="text-sm text-gray-500">{{ __('users.labels.profile_info_update_subtitle') }}</p>
        </x-slot:header>

        <x-form wire:submit.prevent="save" id="user-details-form">
            <div>
                {{-- avatar --}}
                @includeWhen(\Rawilk\LaravelBase\Features::managesAvatars(), 'livewire.admin.users.partials.avatar-upload')

                {{-- name --}}
                <x-form-group label="{{ __('Name') }}" name="name" inline>
                    <x-input
                        wire:model.defer="state.name"
                        name="name"
                        required
                        maxlength="255"
                        max-width=" sm:max-w-xs"
                        placeholder="{{ $user->name }}"
                        autocomplete="off"
                    />
                </x-form-group>

                {{-- email --}}
                <x-form-group name="email" label="{{ __('Email') }}" inline>
                    <x-email
                        wire:model.defer="state.email"
                        name="email"
                        required
                        placeholder="{{ $user->email }}"
                        autocomplete="off"
                    />
                </x-form-group>

                {{-- timezone --}}
                <x-form-group name="timezone" label="{{ __('Timezone') }}" inline>
                    <x-timezone-select
                        wire:model.defer="state.timezone"
                        name="timezone"
                        required
                        :only="timezoneSubsets()"
                        use-custom-select
                    />
                </x-form-group>
            </div>
        </x-form>

        <x-slot:footer>
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="profile.updated" />

                <x-button
                    variant="blue"
                    type="submit"
                    form="user-details-form"
                    wire:target="save"
                >
                    <span>{{ __('base::messages.save_button') }}</span>
                    <x-heroicon-s-check />
                </x-button>
            </div>
        </x-slot:footer>
    </x-card>

    <x-laravel-base::misc.update-title-script action="profile.updated">
        const newTitle = @this.state['name'];

        updateBreadcrumb(newTitle);
    </x-laravel-base::misc.update-title-script>
</div>
