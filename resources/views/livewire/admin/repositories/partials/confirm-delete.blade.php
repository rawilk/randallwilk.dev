<x-dialog-modal max-width="md" wire:model.defer="showDelete">
    <x-slot:title>{{ __('repos.delete.title') }}</x-slot:title>

    <x-slot:content>
        <p>
            {!! Str::inlineMarkdown(__('repos.delete.text', ['name' => $repository?->name])) !!}
        </p>
    </x-slot:content>

    <x-slot:footer>
        <x-button
            variant="red"
            wire:click="deleteRepository"
            wire:target="deleteRepository"
        >
            {{ __('base::messages.delete_button') }}
        </x-button>

        <x-button
            variant="white"
            wire:click="$set('showDelete', false)"
            wire:loading.attr="disabled"
        >
            {{ __('base::messages.confirm_modal_cancel') }}
        </x-button>
    </x-slot:footer>
</x-dialog-modal>
