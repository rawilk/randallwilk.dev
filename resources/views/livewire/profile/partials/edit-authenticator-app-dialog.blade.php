<x-dialog-modal max-width="lg" wire:model.defer="showEdit" :show-icon="false">
    @if ($editing)
        <x-slot name="title">{{ __('base::2fa.authenticator.edit_app_title') }}</x-slot>

        <x-slot name="content">
            <x-form wire:submit.prevent="saveApp" id="authenticatorAppNameForm">
                <x-form-group label="{{ __('base::2fa.authenticator.labels.app_name') }}" name="name" input-id="authenticatorAppName" class="pt-3">
                    <x-input
                        wire:model.defer="editState.name"
                        name="name"
                        id="authenticatorAppName"
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
        </x-slot>

        <x-slot name="footer">
            <x-button
                type="submit"
                form="authenticatorAppNameForm"
                wire:target="saveApp"
                variant="blue"
            >
                {{ __('base::messages.save_button') }}
            </x-button>

            <x-button
                variant="white"
                wire:click="$set('showEdit', false)"
                wire:loading.attr="disabled"
            >
                {{ __('base::messages.confirm_modal_cancel') }}
            </x-button>
        </x-slot>
    @endif
</x-dialog-modal>
