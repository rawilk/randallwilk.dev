<x-card>
    <x-slot:header>
        <h2>{{ __('users.labels.account_info_title') }}</h2>
        <p class="text-sm text-gray-500">{{ __('users.labels.account_info_subtitle') }}</p>
    </x-slot:header>

    {{-- password --}}
    <x-form-group name="password" :label="__('Password')" inline>
        <x-password
            wire:model.defer="password"
            name="password"
            :placeholder="__('Optional')"
        />

        <x-slot:help-text>{{ __('Leave blank to generate a random password for the user. The new user will be emailed their password.') }}</x-slot:help-text>
    </x-form-group>
</x-card>
