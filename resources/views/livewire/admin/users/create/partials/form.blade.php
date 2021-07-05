<div>
    <x-form wire:submit.prevent="createUser" id="create-user-form">
        @include('livewire.admin.users.create.partials.user-details')

        @include('livewire.admin.users.create.partials.account-information')

        <div class="sm:flex sm:flex-row-reverse items-center space-y-3 sm:space-y-0 my-6">
            <span class="flex w-full rounded-md shadow-sm ml-0 sm:ml-3 sm:w-auto">
                <x-button type="submit" variant="blue" wire:target="createUser">
                    <span>{{ __('labels.forms.create_button') }}</span>

                    <x-heroicon-s-check />
                </x-button>
            </span>

            <span class="flex w-full rounded-md shadow-sm sm:w-auto">
                <x-button href="{{ url()->previous() }}" variant="white">
                    {{ __('labels.forms.cancel_button') }}
                </x-button>
            </span>
        </div>
    </x-form>
</div>
