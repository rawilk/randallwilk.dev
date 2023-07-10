<x-dialog-modal wire:model.defer="showDelete" max-width="md">
    <x-slot:title>
        {{ __('users.delete_modal_title') }}
    </x-slot:title>

    <x-slot:content>
        {!! Str::markdown(__('users.delete_modal_text', ['name' => $user?->name->full])) !!}
    </x-slot:content>

    <x-slot:footer>
        <x-button wire:click="deleteUser"
                  text="red"
        >
            {{ __('base::messages.delete_button') }}
        </x-button>

        <x-button wire:click="$set('showDelete', false)"
                  wire:loading.attr="disabled"
                  color="slate"
                  variant="text"
        >
            {{ __('base::messages.confirm_modal_cancel') }}
        </x-button>
    </x-slot:footer>
</x-dialog-modal>
