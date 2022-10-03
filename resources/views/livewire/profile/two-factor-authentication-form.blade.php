<div class="pt-4 first:pt-0">
    <div class="text-base text-slate-600 font-medium mb-3">
        {{ __('base::2fa.authenticator.title') }}
    </div>

    <div @class([
        'md:flex' => $apps->isEmpty(),
    ])>
        <ul role="list"
            @class([
                'space-y-3',
                'md:pr-2 md:flex-1' => $apps->isEmpty(),
            ])
        >
            @forelse ($apps as $authenticator)
                <li wire:key="authenticatorApp{{ $authenticator->getKey() }}">
                    <x-profile.authenticator-app-list-item
                        :authenticator="$authenticator"
                        :confirm-password-enabled="$this->mustConfirmPassword"
                    />
                </li>
            @empty
                <li wire:key="authenticatorAppNoResults">
                    <div class="text-sm text-slate-600">
                        {!! __('base::2fa.authenticator.description') !!}
                    </div>
                </li>
            @endforelse
        </ul>

        <div @class([
            'md:flex-shrink-0 md:ml-4 mt-3 text-right md:mt-0 md:text-left' => $apps->isEmpty(),
            'mt-3 text-right' => $apps->isNotEmpty(),
        ])>
            @if ($canAddMore = $this->canAddMoreApps())
                <x-profile.two-factor-trigger
                    action="showEnable"
                    variant="blue"
                    :confirm-enabled="$this->mustConfirmPassword"
                >
                    {{ $apps->isEmpty() ? __('base::2fa.authenticator.show_setup_button') : __('base::2fa.authenticator.show_set_up_addtl_button') }}
                </x-profile.two-factor-trigger>
            @else
                <x-tooltip title="{{ __('base::2fa.authenticator.alerts.max_reached') }}">
                    <x-button variant="blue" disabled>
                        {{ $apps->isEmpty() ? __('base::2fa.authenticator.show_setup_button') : __('base::2fa.authenticator.show_set_up_addtl_button') }}
                    </x-button>
                </x-tooltip>
            @endif
        </div>
    </div>

    @includeWhen($canAddMore, 'livewire.profile.partials.register-authenticator-app-dialog')

    @if ($apps->isNotEmpty())
        @include('livewire.profile.partials.edit-authenticator-app-dialog')
        @include('livewire.profile.partials.confirm-delete-authenticator-app')
    @endif
</div>
