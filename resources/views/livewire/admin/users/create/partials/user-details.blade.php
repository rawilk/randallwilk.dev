<x-card>
    <x-slot:header>
        <h2>{{ __('Profile Information') }}</h2>
        <p class="text-sm text-gray-500">{{ __("Specify the user's profile information and email address.") }}</p>
    </x-slot:header>

    {{-- avatar --}}
    @includeWhen(\Rawilk\LaravelBase\Features::managesAvatars(), 'livewire.admin.users.partials.avatar-upload')

    {{-- name --}}
    <x-form-group label="{{ __('Name') }}" name="name" inline>
        <x-input
            wire:model.defer="state.name"
            name="name"
            required
            maxlength="255"
            autofocus
            max-width=" sm:max-w-xs"
            placeholder="{{ __('Tom Cooke') }}"
        />
    </x-form-group>

    {{-- email --}}
    <x-form-group label="{{ __('Email address') }}" name="email" inline>
        <x-email
            wire:model.defer="state.email"
            name="email"
            required
            maxlength="255"
            placeholder="{{ __('tom.cooke@:domain', ['domain' => request()->getHost()]) }}"
        />
    </x-form-group>

    {{-- timezone --}}
    <x-form-group label="{{ __('Timezone') }}" name="timezone" inline>
        <x-timezone-select
            wire:model.defer="state.timezone"
            name="timezone"
            required
            use-custom-select
            fixed-position
            :only="timezoneSubsets()"
        />
    </x-form-group>
</x-card>
