<x-dialog-modal max-width="md" wire:model.defer="showDelete">
    <x-slot name="title">{{ __('Delete Repository') }}</x-slot>

    <x-slot name="content">
        <p class="modal-text">
            {!! __(
                'This will soft-delete the repository <strong>:name</strong>; it will still remain in the database.',
                ['name' => $repository->name]
            ) !!}
        </p>
    </x-slot>

    <x-slot name="footer">
        <x-button variant="danger"
                  wire:click="deleteRepository"
                  wire:target="deleteRepository"
        >
            {{ __('Delete Repository') }}
        </x-button>

        <x-button variant="white"
                  wire:click="$set('showDelete', false)"
        >
            {{ __('Nevermind') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
