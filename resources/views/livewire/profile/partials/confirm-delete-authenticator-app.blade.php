<x-dialog-modal max-width="md" wire:model.defer="showDelete">
    @if ($deleting)
        <x-slot name="title">{{ __('base::2fa.authenticator.delete_modal_title') }}</x-slot>

        <x-slot name="content">
            <p>
                {!! __('base::2fa.authenticator.delete_modal_text', ['name' => $deleting->name]) !!}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-button
                variant="red"
                wire:click="deleteApp"
                wire:target="deleteApp"
            >
                {{ __('base::2fa.authenticator.delete_app_button') }}
            </x-button>

            <x-button
                variant="white"
                wire:click="$set('showDelete', false)"
                wire:loading.attr="disabled"
            >
                {{ __('base::messages.confirm_modal_cancel') }}
            </x-button>
        </x-slot>
    @endif
</x-dialog-modal>
