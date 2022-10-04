<x-dialog-modal wire:model.defer="confirmingUserDeletion">
    <x-slot:title>{{ __('users.profile.delete.title') }}</x-slot:title>

    <x-slot:content>
        <p>{!! Str::inlineMarkdown(__('users.profile.delete.confirmation_text')) !!}</p>

        <div class="mt-4">
            <x-password
                wire:model.defer="password"
                wire:keydown.enter="deleteUser"
                name="password"
                placeholder="{{ __('users.form.labels.password') }}"
                focus
            />

            <x-form-error name="password" />
        </div>
    </x-slot:content>

    <x-slot:footer>
        <x-button
            wire:click="deleteUser"
            wire:target="deleteUser"
            variant="red"
        >
            {{ __('users.profile.delete.trigger') }}
        </x-button>

        <x-button
            wire:click="$set('confirmingUserDeletion', false)"
            wire:loading.attr="disabled"
            variant="white"
        >
            {{ __('base::messages.confirm_modal_cancel') }}
        </x-button>
    </x-slot:footer>
</x-dialog-modal>
