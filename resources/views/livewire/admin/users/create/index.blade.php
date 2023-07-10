<div>
    <x-form wire:submit.prevent="store" id="create-user-form">
        <div class="space-y-6">
            @include('livewire.admin.users.create.partials.user-details')

            @include('livewire.admin.users.create.partials.account-information')

            @if ($this->canAssignRoles || $this->canAssignPermissions)
                @include('livewire.admin.users.create.partials.user-abilities')
            @endif
        </div>

        <div class="content-container lg:flex lg:flex-row-reverse lg:items-center my-6" x-data>
            <div class="flex w-full lg:ml-3 lg:w-auto">
                <x-button
                    type="submit"
                    color="blue"
                    wire:target="store"
                    block
                    right-icon="heroicon-s-check"
                >
                    {{ __('base::messages.create_button') }}
                </x-button>
            </div>

            <div class="mt-3 flex w-full lg:mt-0 lg:w-auto">
                <x-button
                    color="slate"
                    variant="text"
                    :href="route('admin.users.index')"
                    block
                >
                    {{ __('base::messages.cancel_button') }}
                </x-button>
            </div>
        </div>
    </x-form>
</div>
