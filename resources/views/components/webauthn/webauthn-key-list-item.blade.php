@props([
    'securityKey',
    'icon',
    'confirmPasswordEnabled' => false,
])

<div class="rounded-md bg-gray-100 py-3 px-3">
    <div class="flex items-start">
        <div class="min-w-0 flex-1 flex items-start">
            <div class="flex-shrink-0 pt-0.5">
                <x-dynamic-component :component="$icon" class="h-4 w-4 text-slate-500" />
            </div>

            <div class="min-w-0 flex-1 pl-2">
                <div>
                    <p class="text-sm font-medium text-slate-600 truncate">{{ $securityKey->name }}</p>

                    <div class="mt-2">
                        <p class="text-sm text-slate-500">
                            {!! __('base::webauthn.labels.created_at', ['date' => $securityKey->createdAtHtml(userTimezone())]) !!}
                        </p>
                        <p class="text-sm text-slate-500">
                            {!! __('base::webauthn.labels.last_used_at', ['date' => $securityKey->lastUsedAtHtml(userTimezone())]) !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-row md:flex space-y-2 md:space-x-3 md:space-y-0">
            @if ($confirmPasswordEnabled)
                <x-confirms-password wire:then="confirmDelete({{ $securityKey->getKey() }})" class="inline-flex">
                    <x-laravel-base::button.link
                        wire:target="confirmDelete"
                        class="text-xs"
                    >
                        {{ __('base::webauthn.delete_key_button') }}
                    </x-laravel-base::button.link>
                </x-confirms-password>
            @else
                <x-laravel-base::button.link
                    wire:click="confirmDelete({{ $securityKey->getKey() }})"
                    wire:target="confirmDelete"
                    class="text-xs"
                >
                    {{ __('base::webauthn.delete_key_button') }}
                </x-laravel-base::button.link>
            @endif

            <x-laravel-base::button.link
                wire:click="editKey({{ $securityKey->getKey() }})"
                wire:target="editKey"
                class="text-xs"
            >
                {{ __('base::messages.edit_button') }}
            </x-laravel-base::button.link>
        </div>
    </div>
</div>
