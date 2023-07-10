<div class="pt-4 first:pt-0">
    <div class="text-base text-slate-600 font-medium mb-3">
        {{ __('base::2fa.recovery.title') }}
    </div>

    <div class="md:flex">
        <div class="md:flex-1 md:pr-2">
            <p class="text-sm text-slate-600">{{ __('base::2fa.recovery.description') }}</p>
        </div>

        <div class="md:flex-shrink-0 md:ml-4 mt-3 text-right md:mt-0 md:text-left">
            @if ($enabled)
                <x-profile.two-factor-trigger
                    action="showCodes"
                    variant="blue"
                    :confirm-enabled="$this->mustConfirmPassword"
                >
                    {{ __('base::2fa.recovery.show_codes_button') }}
                </x-profile.two-factor-trigger>
            @else
                <div> {{-- wrapped in div to prevent DOM diffing issues from livewire --}}
                    <x-laravel-base::elements.tooltip :title="__('base::2fa.recovery.show_codes_disabled_help')">
                        <x-button
                            color="blue"
                            disabled
                        >
                            {{ __('base::2fa.recovery.show_codes_button') }}
                        </x-button>
                    </x-laravel-base::elements.tooltip>
                </div>
            @endif
        </div>
    </div>

    <x-dialog-modal max-width="lg" wire:model.defer="showingCodes" :show-icon="false">
        @if ($showingCodes)
            <x-slot name="title">{{ __('base::2fa.recovery.title') }}</x-slot>

            <x-slot name="content">
                <p>{!! __('base::2fa.recovery.modal_text') !!}</p>

                <div class="mt-3 flex justify-between px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg relative">
                    <div class="grid gap-1 flex-1">
                        @foreach ($user->recoveryCodes() as $code)
                            <div>{{ $loop->index + 1 }}. {{ $code }}</div>
                        @endforeach
                    </div>

                    <div class="flex-shrink-0">
                        <x-laravel-base::misc.copy-to-clipboard :text="$user->recoveryCodes()" />
                    </div>
                </div>

                <div class="mt-6 text-sm">
                    {{ __('base::2fa.recovery.regenerate_warning') }}
                </div>

                <div class="mt-4 text-center">
                    <x-profile.two-factor-trigger action="regenerate" color="blue" :confirm-enabled="$this->mustConfirmPassword">
                        {{ __('base::2fa.recovery.regenerate_button') }}
                    </x-profile.two-factor-trigger>
                </div>
            </x-slot>
        @endif
    </x-dialog-modal>
</div>
