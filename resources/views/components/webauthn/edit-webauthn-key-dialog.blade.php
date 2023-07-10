@props([
    'editing' => false,
    'prefix' => Str::random(8),
])

<x-dialog-modal max-width="lg" wire:model.defer="showEdit" :show-icon="false">
    @if ($editing)
        <x-slot:title>{{ __('base::webauthn.edit_key_title') }}</x-slot:title>

        <x-slot:content>
            <x-form wire:submit.prevent="saveKey" id="{{ $prefix }}NameForm">
                <x-form-group label="{{ __('base::webauthn.labels.key_name') }}" name="name" input-id="{{ $prefix }}Name" class="pt-3">
                    <x-input
                        wire:model.defer="editState.name"
                        name="name"
                        id="{{ $prefix }}Name"
                        focus
                        required
                        maxlength="255"
                    />
                </x-form-group>

                <p class="mt-4 text-sm text-slate-500">
                    {!! __('base::webauthn.labels.created_at', ['date' => $editing->createdAtHtml(userTimezone())]) !!}
                </p>

                <div class="mt-1 text-sm text-slate-500">
                    {!! __('base::webauthn.labels.last_used_at', ['date' => $editing->lastUsedAtHtml(userTimezone())]) !!}
                </div>
            </x-form>
        </x-slot:content>

        <x-slot:footer>
            <x-button
                type="submit"
                form="{{ $prefix }}NameForm"
                wire:target="saveKey"
                color="blue"
            >
                {{ __('base::messages.save_button') }}
            </x-button>

            <x-button
                color="slate"
                variant="text"
                wire:click="$set('showEdit', false)"
                wire:loading.attr="disabled"
            >
                {{ __('base::messages.confirm_modal_cancel') }}
            </x-button>
        </x-slot:footer>
    @endif
</x-dialog-modal>
