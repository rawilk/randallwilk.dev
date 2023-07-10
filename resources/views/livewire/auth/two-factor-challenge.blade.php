<div>
    @if ($this->canSecurityKey())
        @push('head')
            @webauthnScripts
        @endpush
    @endif

    <x-auth.authentication-form :title="__('base::2fa.challenge.title')">
        <div x-data="{
                challengeType: @entangle('currentChallengeType').defer,
                recoveryCode: @entangle('recoveryCode').defer,
                showOptions: false,
                chooseType(type) {
                    this.challengeType = type;
                    this.removeErrors();

                    switch (type) {
                        case 'backup_code':
                            this.recoveryCode = '';
                            this.$nextTick(() => this.$refs.recoveryCode.focus());
                            break;
                        case 'totp':
                            this.$nextTick(() => {
                                this.$dispatch('otp-reset');
                                this.$dispatch('focus-otp');
                            });

                            break;
                        case 'key':
                            this.webAuthnAuthenticate();

                            break;
                    }

                    this.showOptions = false;
                },
                removeErrors() {
                    try {
                        ['two-factor-error-alert', 'two_factor-error'].forEach(errorId => {
                            const el = document.getElementById(errorId);
                            el && el.remove();
                        });
                    } catch {}
                },
                @if ($this->canSecurityKey())
                publicKey: @entangle('publicKey').defer,
                keyData: @entangle('keyData').defer,
                webAuthnSupported: true,
                errorMessages: {{ Js::from($this->errorMessages) }},
                errorMessage: null,
                notifyCallback() {
                    return (errorName, defaultMessage) => {
                        this.errorMessage = this.errorMessages[errorName] || defaultMessage;
                    };
                },
                webAuthn: new WebAuthn,
                webAuthnAuthenticate() {
                    if (! this.webAuthnSupported) {
                        return;
                    }

                    this.keyData = null;
                    this.errorMessage = null;
                    this.removeErrors();

                    this.webAuthn.sign(JSON.parse(this.publicKey), data => {
                        this.keyData = data;
                        @this.login();
                    });
                },
                init() {
                    this.webAuthnSupported = this.webAuthn.supported();
                    if (! this.webAuthnSupported) {
                        this.errorMessage = this.errorMessages[this.webAuthn.notSupportedType()];
                    }

                    this.webAuthn.registerNotifyCallback(this.notifyCallback());
                    this.webAuthnAuthenticate();
                },
                @endif
             }"
             @if ($this->canTotp()) x-on:otp-finish.window="$wire.login()" @endif
        >
            <x-form-error name="two_factor" wire:key="two-factor-error">
                @php($twoFactorError = $component->messages($errors)[0] ?? '')

                <x-alert :type="\Rawilk\LaravelBase\Components\Alerts\Alert::ERROR" class="mb-4" id="two-factor-error-alert">
                    <p>{!! $twoFactorError !!}</p>
                </x-alert>
            </x-form-error>

            <x-form wire:submit.prevent="login">
                <div x-cloak x-show="! showOptions">
                    {{-- recovery code --}}
                    <div x-show="challengeType === 'backup_code'" wire:key="backup_code">
                        <div class="text-slate-600 mb-4">{{ __('base::2fa.challenge.recovery_title') }}</div>

                        <x-form-group :label="__('base::2fa.challenge.recovery_code_label')" name="recoveryCode">
                            <x-input
                                x-model="recoveryCode"
                                x-bind:required="challengeType === 'backup_code'"
                                name="recoveryCode"
                                x-ref="recoveryCode"
                            />
                        </x-form-group>
                    </div>

                    {{-- totp code --}}
                    @if ($this->canTotp())
                        <div x-show="challengeType === 'totp'" wire:key="totp">
                            <div class="text-slate-600 mb-4">{{ __('base::2fa.challenge.app_code_title') }}</div>

                            <x-form-group :label="false" name="totp">
                                <x-laravel-base::inputs.otp
                                    name="totp"
                                    wire:model.defer="totp"
                                />
                            </x-form-group>
                        </div>
                    @endif

                    {{-- security key challenge --}}
                    @if ($this->canSecurityKey())
                        <div x-show="challengeType === 'key'" wire:key="security_key">
                            <div x-show="! errorMessage">
                                <div class="text-slate-600 mb-4">{{ __('base::2fa.challenge.webauthn_waiting_title') }}</div>

                                <div class="my-6">
                                    <x-misc.loading-spinner class="h-10 w-10 text-slate-500" />
                                </div>
                            </div>

                            <div x-show="errorMessage" class="mb-10">
                                <div class="flex justify-center mt-4 mb-2">
                                    <x-css-info class="h-10 w-10 text-red-500" />
                                </div>

                                <p class="text-base text-center text-slate-600" x-html="errorMessage"></p>

                                <div class="mt-10 text-center" x-show="webAuthnSupported">
                                    <x-button
                                        variant="blue"
                                        x-on:click="errorMessage = null; webAuthnAuthenticate();"
                                    >
                                        {{ __('base::2fa.challenge.webauthn_retry_button') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6" x-show="challengeType === 'backup_code' && ! showOptions" x-cloak>
                    <x-button
                        variant="blue"
                        type="submit"
                        block
                        wire:target="login"
                    >
                        {{ __('base::2fa.challenge.verify_recovery_button') }}
                    </x-button>
                </div>
            </x-form>

            {{-- available options --}}
            <div x-cloak x-show="showOptions" class="divide-y divide-y-slate-300">
                @foreach ($challengeOptions as $challengeOption)
                    <div wire:key="challengeOption-{{ $challengeOption }}"
                         role="button"
                         x-on:click="chooseType('{{ $challengeOption }}')"
                         class="py-3 pr-3 hover:bg-gray-100 text-slate-500 hover:text-slate-600 transition-colors group"
                    >
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-1.5 pt-1 pl-2">
                                <x-dynamic-component
                                    :component="$this->challengeOptionIcon($challengeOption)"
                                    class="h-4 w-4 text-slate-400 group-hover:text-slate-500"
                                />
                            </div>
                            <div>
                                {{ $this->challengeOptionName($challengeOption) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4" x-show="! showOptions">
                <div class="text-slate-600">
                    <span x-show="challengeType === 'backup_code'">{!! __('base::2fa.challenge.cant_access_codes') !!}</span>
                    <span x-show="challengeType !== 'backup_code'">{!! __('base::2fa.challenge.cant_access_device') !!}</span>
                    <x-laravel-base::button.link x-on:click="showOptions = true; removeErrors();">
                        {{ __('base::2fa.challenge.see_other_options_button') }}
                    </x-laravel-base::button.link>
                </div>
            </div>
        </div>
    </x-auth.authentication-form>
</div>
