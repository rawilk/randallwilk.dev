<x-dialog-modal wire:model.defer="showDelete" max-width="md">
    <x-slot name="title">
        {{ __('users.labels.delete_modal_title') }}
    </x-slot>

    <x-slot name="content">
        <p class="modal-text">
            {!! __(
                'users.labels.delete_modal_text',
                ['name' => $user->name->full]
            ) !!}
        </p>
    </x-slot>

    <x-slot name="footer">
        <x-button wire:click="deleteUser"
                  wire:target="deleteUser"
                  variant="red"
        >
            {{ __('users.labels.delete_modal_button') }}
        </x-button>

        <x-button wire:click="$set('showDelete', false)"
                  wire:loading.attr="disabled"
                  variant="white"
        >
            {{ __('labels.confirm_modal_cancel') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
