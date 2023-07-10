<div>
    <x-card>
        <h3 class="text-lg leading-6 font-medium text-slate-900">{{ __('users.profile.delete.title') }}</h3>
        <p class="mt-2 max-w-xl text-sm text-slate-500">{{ __('users.profile.delete.warning') }}</p>

        <div class="mt-5">
            <x-button color="red" wire:click="confirmUserDeletion">
                {{ __('users.profile.delete.trigger') }}
            </x-button>
        </div>
    </x-card>

    @include('livewire.profile.partials.delete-user-dialog')
</div>
