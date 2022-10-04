<x-card>
    <x-slot:header>
        <h2>{{ __('User Abilities') }}</h2>
        <p class="text-sm text-gray-500">{{ __('Define what the user is allowed to do in the application.') }}</p>
    </x-slot:header>

    @include('livewire.admin.users.partials.user-abilities-form')
</x-card>
