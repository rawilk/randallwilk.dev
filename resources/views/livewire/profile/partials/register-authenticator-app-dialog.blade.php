<div x-data="{
    showInstructions: @entangle('showEnableInstructions').defer,
    showCode() {
        this.showInstructions = false;

        setTimeout(() => this.$dispatch('focus-otp'), 250);
    },
    completeSetup() {
        @this.enableTwoFactorAuthentication();
    },
}"
     x-on:otp-finish="completeSetup"
>
    <x-dialog-modal max-width="lg" wire:model.defer="showingEnable" :show-icon="false">
        <x-slot name="title">
            <span x-show="showInstructions">{{ __('base::2fa.authenticator.enable_modal_title') }}</span>
            <span x-show="! showInstructions">{{ __('base::2fa.authenticator.complete_setup_title') }}</span>
        </x-slot>

        <x-slot name="content">
            <div x-show="showInstructions">
                <div class="pt-3">
                    <p class="text-base text-slate-600">{{ __('base::2fa.authenticator.setup_instructions_title') }}</p>

                    <ol class="mt-2 list-decimal pl-4 sm:pl-6">
                        <li>{!! __('base::2fa.authenticator.setup_download_instruction') !!}</li>
                        <li>{!! __('base::2fa.authenticator.setup_scan_instruction') !!}</li>
                        <li>{!! __('base::2fa.authenticator.setup_scan_qr_code_instruction') !!}</li>
                    </ol>
                </div>

                @if ($twoFactorSecret)
                    <div class="mt-4 flex justify-center">
                        {!! $this->qrCodeSvg() !!}
                    </div>
                @endif

                <div class="mt-4">
                    <p class="text-sm text-slate-600">
                        {!! __('base::2fa.authenticator.qr_code_help') !!}
                    </p>

                    @if ($twoFactorSecret)
                        <div class="relative mt-2 rounded-md px-3 py-3 relative bg-gray-100 text-sm font-mono">
                            <span>{{ decrypt($twoFactorSecret) }}</span>

                            <div class="absolute top-0 right-1 pr-1 pt-1">
                                <x-laravel-base::misc.copy-to-clipboard class="border-none" text="{{ decrypt($twoFactorSecret) }}" />
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 text-center">
                    <x-button
                        variant="blue"
                        x-on:click="showCode"
                    >
                        {{ __('base::2fa.authenticator.setup_continue_button') }}
                    </x-button>
                </div>
            </div>

            <div x-show="! showInstructions">
                <div wire:loading.remove.delay wire:target="enableTwoFactorAuthentication">
                    <div class="pt-3">
                        <p class="text-base text-slate-600">{!! __('base::2fa.authenticator.complete_setup_instructions') !!}</p>
                    </div>

                    <div x-ref="otpInput">
                        <x-form-group :label="false" name="confirmationCode" class="mt-4">
                            <x-laravel-base::inputs.otp
                                name="confirmationCode"
                                wire:model.defer="confirmationCode"
                            />
                        </x-form-group>
                    </div>

                    <div class="mt-6">
                        <x-laravel-base::button.link dark x-on:click.prevent.stop="showInstructions = true" class="flex items-center">
                            <x-css-chevron-left class="h-4 w-4 mr-1" />
                            <span>{{ __('base::2fa.authenticator.setup_back_button') }}</span>
                        </x-laravel-base::button.link>
                    </div>
                </div>

                <div wire:loading.class.remove.delay="hidden" class="hidden" wire:target="enableTwoFactorAuthentication">
                    <div class="my-6 mx-auto flex justify-center w-auto">
                        <x-misc.loading-spinner class="h-10 w-10 text-slate-500" />
                    </div>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
