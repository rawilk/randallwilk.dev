<div>
    <x-card>
        <x-slot:header>
            <h2>{{ __('users.labels.user_abilities_title') }}</h2>
            <p class="text-sm text-gray-500">{{ __('users.labels.user_abilities_subtitle') }}</p>
        </x-slot:header>

        <x-form wire:submit.prevent="updateAbilities" id="update-abilities-form">
            @include('livewire.admin.users.partials.user-abilities-form')
        </x-form>

        <x-slot:footer>
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="abilities.updated" />

                <x-button type="submit" variant="blue" form="update-abilities-form" wire:target="updateAbilities">
                    <span>{{ __('base::messages.save_button') }}</span>
                    <x-heroicon-s-check />
                </x-button>
            </div>
        </x-slot:footer>
    </x-card>
</div>
