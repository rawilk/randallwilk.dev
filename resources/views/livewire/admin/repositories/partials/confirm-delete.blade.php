<x-dialog-modal max-width="md" wire:model.defer="showDelete">
    <x-slot:title>{{ __('repos.delete.title') }}</x-slot:title>

    <x-slot:content>
        {!! Str::markdown(__('repos.delete.text', ['name' => $repository?->name])) !!}
    </x-slot:content>

    <x-slot:footer>
        <x-button
            color="red"
            wire:click="deleteRepository"
        >
            {{ __('base::messages.delete_button') }}
        </x-button>

        <x-button
            variant="text"
            color="slate"
            wire:click="$set('showDelete', false)"
            wire:loading.attr="disabled"
        >
            {{ __('base::messages.confirm_modal_cancel') }}
        </x-button>
    </x-slot:footer>
</x-dialog-modal>
