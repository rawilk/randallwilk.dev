<div>
    <div class="bg-white shadow lg:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ __('users.profile.account_info.delete_user_title') }}
            </h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>
                    {{ __('users.profile.account_info.delete_user_warning') }}
                </p>
            </div>
            <div class="mt-5">
                <x-button variant="danger" wire:click="confirmUserDeletion">
                    {{ __('users.profile.account_info.delete_account_trigger_button') }}
                </x-button>
            </div>
        </div>
    </div>

    @include('livewire.profile.partials.confirm-delete-dialog')
</div>
