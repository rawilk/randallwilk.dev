<div class="pt-4 first:pt-0">
    <div class="text-base text-slate-600 font-medium mb-3">{{ __('base::webauthn.internal_keys_title') }}</div>

    <div @class([
        'md:flex' => $securityKeys->isEmpty(),
    ])>
        <ul role="list"
            @class([
                'space-y-3',
                'md:pr-2 md:flex-1' => $securityKeys->isEmpty(),
            ])
        >
            @forelse ($securityKeys as $securityKey)
                <li wire:key="webauthnInternalKey{{ $securityKey->getKey() }}">
                    <x-webauthn.webauthn-key-list-item
                        :security-key="$securityKey"
                        :confirm-password-enabled="$this->mustConfirmPassword"
                        icon="heroicon-s-computer-desktop"
                    />
                </li>
            @empty
                <li wire:key="webauthnInternalKeysNoResults">
                    <div class="text-sm text-slate-600">
                        {{ __('base::webauthn.internal_keys_no_results') }}
                    </div>
                </li>
            @endforelse
        </ul>

        <div @class([
            'md:flex-shrink-0 md:ml-4 mt-3 text-right md:mt-0 md:text-left' => $securityKeys->isEmpty(),
            'mt-3 text-right' => $securityKeys->isNotEmpty(),
        ])>
            @if ($canAddMore = $this->canAddMoreKeys())
                <x-profile.two-factor-trigger
                    variant="blue"
                    action="showAddKey"
                    :confirm-enabled="$this->mustConfirmPassword"
                >
                    {{ __('base::webauthn.add_internal_key_button') }}
                </x-profile.two-factor-trigger>
            @else
                <x-tooltip title="{{ __('base::webauthn.alerts.max_reached') }}">
                    <x-button variant="blue" disabled>
                        {{ __('base::webauthn.add_internal_key_button') }}
                    </x-button>
                </x-tooltip>
            @endif
        </div>
    </div>

    @if ($canAddMore)
        <x-webauthn.register-webauthn-key-dialog
            title="{{ __('base::webauthn.register_internal_title') }}"
            :needs-instructions="false"
            prefix="internalKey"
        />
    @endif

    @if ($securityKeys->isNotEmpty())
        <x-webauthn.edit-webauthn-key-dialog
            :editing="$editing"
            prefix="internalKey"
        />

        @include('livewire.profile.partials.confirm-delete-webauthn-key')
    @endif
</div>
