<x-dialog-modal max-width="md" wire:model.defer="showDelete">
    @if ($deleting)
        <x-slot name="title">{{ __('base::webauthn.delete_modal_title') }}</x-slot>

        <x-slot name="content">
            <p>
                {!! __('base::webauthn.delete_modal_text', ['name' => $deleting->name]) !!}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-button
                color="red"
                wire:click="deleteKey"
            >
                {{ __('base::webauthn.delete_key_button') }}
            </x-button>

            <x-button
                color="slate"
                variant="text"
                wire:click="$set('showDelete', false)"
                wire:loading.attr="disabled"
            >
                {{ __('base::messages.confirm_modal_cancel') }}
            </x-button>
        </x-slot>
    @endif
</x-dialog-modal>
