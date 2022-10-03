<div class="pt-4 first:pt-0">
    <div class="text-base text-slate-600 font-medium mb-3">
        <span>{{ __('base::webauthn.security_keys_title') }}</span>
        <x-badge variant="green">{{ __('base::messages.recommended') }}</x-badge>
    </div>

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
                <li wire:key="webauthnSecurityKey{{ $securityKey->getKey() }}">
                    <x-webauthn.webauthn-key-list-item
                        :security-key="$securityKey"
                        :confirm-password-enabled="$this->mustConfirmPassword"
                        icon="css-usb"
                    />
                </li>
            @empty
                <li wire:key="webauthnSecurityKeysNoResults">
                    <div class="text-sm text-slate-600">
                        @php
                            $link = bladeRender('<x-link href="https://yubico.com/store" class="mr-4" target="_blank">YubiKey</x-link>');
                        @endphp

                        {!! __('base::webauthn.security_key_no_results', ['link' => $link]) !!}
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
                    confirmable-id="securityKeyFormShowAddKey"
                >
                    {{ __('base::webauthn.add_security_key_button') }}
                </x-profile.two-factor-trigger>
            @else
                <x-tooltip title="{{ __('base::webauthn.alerts.max_reached') }}">
                    <x-button variant="blue" disabled>
                        {{ __('base::webauthn.add_security_key_button') }}
                    </x-button>
                </x-tooltip>
            @endif
        </div>
    </div>

    @if ($canAddMore)
        <x-webauthn.register-webauthn-key-dialog
            title="{{ __('base::webauthn.register_security_key_title') }}"
            graphic="{{ asset('images/webauthn-key-example.png') }}"
            instructions="{{ __('base::webauthn.register_security_key_instructions') }}"
            prefix="securityKey"
        />
    @endif

    @if ($securityKeys->isNotEmpty())
        <x-webauthn.edit-webauthn-key-dialog
            :editing="$editing"
            prefix="securityKey"
        />

        @include('livewire.profile.partials.confirm-delete-webauthn-key')
    @endif
</div>
