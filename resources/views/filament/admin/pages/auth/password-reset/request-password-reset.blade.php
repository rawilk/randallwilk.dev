<x-filament-panels::page.simple>

    @if ($email)
        <div>
            <x-feedback.alert
                color="success"
                icon="heroicon-o-check-circle"
                :title="__('pages/auth/request-password-reset.alerts.sent.title')"
            >
                <div class="space-y-3">
                    {{ str(__('pages/auth/request-password-reset.alerts.sent.description', ['email' => e($email)]))->markdown()->toHtmlString() }}
                </div>

                <x-slot:actions>
                    <x-feedback.alert-action
                        wire:click="resend"
                    >
                        {{ __('pages/auth/request-password-reset.actions.resend.label') }}
                    </x-feedback.alert-action>
                </x-slot:actions>
            </x-feedback.alert>
        </div>
    @else
        <x-filament-panels::form wire:submit="request">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>
    @endif

    @if (auth()->guest() && filament()->hasLogin())
        <div class="mt-4">
            {{ $this->loginAction }}
        </div>
    @endif

</x-filament-panels::page.simple>
