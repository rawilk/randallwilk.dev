@props([
    'instructions' => false,
    'graphic' => false,
    'needsInstructions' => true,
    'title' => '',
    'prefix' => Str::random(8),
])

<div x-data="{
    keyName: @entangle('newKeyName').defer,
    showingModal: @entangle('showAddKey').defer,
    showInstructions: @if ($needsInstructions) @entangle('showInstructions').defer, @else false, @endif
    showName: false,
    errorMessage: null,
    notifyCallback() {
        return (errorName, defaultMessage) => {
            this.errorMessage = this.errorMessages[errorName] || defaultMessage;
        };
    },
    webAuthn: new WebAuthn,
    webAuthnSupported: true,
    errorMessages: {{ Js::from($this->errorMessages) }},
    keyData: null,
    publicKey: @entangle('publicKey').defer,
    init() {
        this.webAuthnSupported = this.webAuthn.supported();
        if (! this.webAuthnSupported) {
            this.errorMessage = this.errorMessages[this.webAuthn.notSupportedType()];
        }

        this.webAuthn.registerNotifyCallback(this.notifyCallback());

        @unless ($needsInstructions)
            this.$watch('showingModal', show => {
                if (show) {
                    this.register();
                }
            });
        @endunless
    },
    register() {
        if (! this.webAuthnSupported) {
            return;
        }

        this.errorMessage = null;
        this.keyData = null;
        this.showName = false;
        this.showInstructions = false;

        this.webAuthn.register(JSON.parse(this.publicKey), (publicKeyCredential, deviceName) => {
            this.keyName = deviceName;
            this.keyData = publicKeyCredential;
            this.showName = true;
            this.$nextTick(() => {
                const input = document.getElementById('{{ $prefix }}NewKeyName');

                setTimeout(() => input && input.focus(), 250);
            });
        });
    },
    sendKey() {
        if (! this.keyData) {
            return;
        }

        @this.registerKey(this.keyData);
    },
}"
>
    <x-dialog-modal
        wire:model.defer="showAddKey"
        max-width="lg"
        :show-icon="false"
    >
        <x-slot:title>{{ $title }}</x-slot:title>

        <x-slot:content>
            @if ($needsInstructions)
                <div x-show="showInstructions && webAuthnSupported">
                    <p>{{ $instructions }}</p>

                    @if ($graphic)
                        <div class="mt-4">
                            <img src="{{ $graphic }}" class="max-w-full">
                        </div>
                    @endif
                </div>
            @endif

            <div x-show="! showInstructions || ! webAuthnSupported">
                {{-- we are waiting for user to interact with their authenticator --}}
                <div x-show="! showName && ! errorMessage && webAuthnSupported">
                    <p class="text-center text-2xl mb-4 mt-8">{{ __('base::webauthn.register_waiting_for_interact') }}</p>

                    <div class="mx-auto flex justify-center w-auto">
                        <x-misc.loading-spinner class="h-10 w-10 text-slate-500" />
                    </div>
                </div>

                {{-- an error has occurred (user probably canceled) --}}
                <div x-show="errorMessage">
                    <div class="flex justify-center mt-4 mb-2">
                        <x-css-info class="h-10 w-10 text-red-500" />
                    </div>

                    <p class="text-base text-center" x-html="errorMessage"></p>

                    <div class="mt-6 text-center" x-show="webAuthnSupported">
                        <x-button
                            color="blue"
                            x-on:click="register"
                        >
                            {{ __('base::webauthn.register_retry_button') }}
                        </x-button>
                    </div>
                </div>

                {{-- user needs to name their new key --}}
                <div x-show="showName" class="pt-4">
                    <x-form-group label="{{ __('base::webauthn.labels.key_name') }}" name="newKeyName" input-id="{{ $prefix }}newKeyName">
                        <x-input
                            x-model="keyName"
                            name="newKeyName"
                            id="{{ $prefix }}NewKeyName"
                            x-ref="name"
                            required
                            x-on:keydown.enter.prevent.stop="keyName && sendKey"
                        />

                        <x-slot:help-text>{{ __('base::webauthn.labels.key_name_help') }}</x-slot:help-text>
                    </x-form-group>
                </div>
            </div>
        </x-slot:content>

        <x-slot:footer>
            <x-button
                x-show="showInstructions"
                x-on:click="register"
                color="blue"
                focus
            >
                {{ __('base::webauthn.register_next_button') }}
            </x-button>

            <x-button
                x-show="! showInstructions && showName"
                x-bind:disabled="! keyName"
                x-on:click="sendKey"
                wire:target="registerKey"
                color="blue"
                class="-mr-4"
            >
                {{ __('base::webauthn.register_button') }}
            </x-button>

            <x-button
                x-show="! showInstructions && showName"
                color="slate"
                variant="text"
                wire:click="$set('showAddKey', false)"
            >
                {{ __('base::messages.confirm_modal_cancel') }}
            </x-button>
        </x-slot:footer>
    </x-dialog-modal>
</div>
