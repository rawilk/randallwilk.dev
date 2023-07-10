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
            <div class="flex justify-end items-center space-x-4" x-data>
                <x-action-message on="abilities.updated" />

                <x-button
                    type="submit"
                    color="blue"
                    form="update-abilities-form"
                    wire:target="updateAbilities"
                    right-icon="heroicon-m-check"
                >
                    {{ __('base::messages.save_button') }}
                </x-button>
            </div>
        </x-slot:footer>
    </x-card>
</div>
