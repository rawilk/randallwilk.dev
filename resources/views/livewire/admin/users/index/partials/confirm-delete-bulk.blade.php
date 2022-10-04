<x-laravel-base::modal.dialog-modal wire:model.defer="showBulkDelete" max-width="md">
    <x-slot:title>{{ __('base::users.confirm_bulk_delete.title') }}</x-slot:title>

    <x-slot:content>
        {!! Str::markdown(__('base::users.confirm_bulk_delete.text')) !!}
    </x-slot:content>

    <x-slot:footer>
        <x-button
            wire:click="deleteSelected"
            wire:target="deleteSelected"
            variant="red"
        >
            {{ __('base::messages.delete_button') }}
        </x-button>

        <x-button
            wire:click="$set('showBulkDelete', false)"
            wire:loading.attr="disabled"
            variant="white"
        >
            {{ __('base::messages.confirm_modal_cancel') }}
        </x-button>
    </x-slot:footer>
</x-laravel-base::modal.dialog-modal>
