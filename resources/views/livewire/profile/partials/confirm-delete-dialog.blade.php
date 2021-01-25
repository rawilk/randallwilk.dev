<x-dialog-modal wire:model.defer="confirmingUserDeletion">
    <x-slot name="title">{{ __('users.profile.account_info.confirm_delete_title') }}</x-slot>

    <x-slot name="content">
        <p class="modal-text">{!! __('users.profile.account_info.confirm_delete_text') !!}</p>

        <div class="mt-4">
            <x-password wire:model.defer="deleteUserPassword"
                        wire:keydown.enter="deleteUser"
                        name="deleteUserPassword"
                        placeholder="{{ __('Password') }}"
                        autofocus
            />

            <x-form-error name="deleteUserPassword" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-button wire:click="deleteUser"
                  wire:target="deleteUser"
                  variant="danger"
        >
            {{ __('users.profile.account_info.confirm_delete_button') }}
        </x-button>

        <x-button wire:click="$toggle('confirmingUserDeletion')"
                  wire:loading.attr="disabled"
                  variant="white"
        >
            {{ __('labels.confirm_modal_cancel') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
