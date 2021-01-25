<div class="grid grid-cols-6">
    <div class="col-span-6 sm:col-span-4 space-y-4">
        <x-label>{{ __('users.form.labels.avatar') }}</x-label>

        {{-- current avatar --}}
        @if ($this->user->getKey() && (! $photo || $errors->has('photo')))
            <div id="current-photo">
                <img src="{{ $this->user->avatar_url }}" alt="{{ $this->user->name->full }}" class="rounded-full h-20 w-20 object-cover" />
            </div>
        @endif

        {{-- new avatar preview --}}
        @if ($photo && ! $errors->has('photo'))
            <div id="new-photo">
                <span class="block rounded-full w-20 h-20"
                      style="background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url('{{ $photo->temporaryUrl() }}');"
                >
                </span>
            </div>
        @endif

        <x-file-upload wire:model="photo"
                       name="photo"
                       label="{{ __('users.form.labels.select_new_avatar') }}"
        />

        <div>
            @if ($photo)
                <x-button wire:click="cancelUpload" variant="danger" id="cancel-upload-button">
                    {{ __('users.form.labels.cancel_avatar_upload') }}
                </x-button>
            @elseif ($this->user->avatar_path)
                <x-button wire:click="deleteProfilePhoto"
                          variant="danger"
                          id="remove-photo-button"
                >
                    {{ __('users.form.labels.remove_avatar') }}
                </x-button>
            @endif
        </div>

        <x-form-error name="photo" />
    </div>
</div>
