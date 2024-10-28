<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Enums\UserSetting;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Rawilk\ProfileFilament\Enums\Livewire\MfaChallengeMode;
use Rawilk\ProfileFilament\Enums\Livewire\MfaEvent;
use Rawilk\ProfileFilament\Facades\Mfa;

/**
 * @property-read array $availableMethods
 * @property-read bool $hasPasskeys
 * @property-read bool $showForm
 * @property-read User $user
 * @property Form $form
 */
class PreferredMfaMethod extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?string $selectedMethod = null;

    public function updatedSelectedMethod(): void
    {
        $this->store();
    }

    #[Computed]
    public function availableMethods(): array
    {
        $options = [];

        if (Mfa::canUseAuthenticatorAppsForChallenge($this->user)) {
            $options[MfaChallengeMode::App->value] = __('users/profile.preferred_mfa.form.method.options.app');
        }

        if (Mfa::canUseWebauthnForChallenge($this->user)) {
            $options[MfaChallengeMode::Webauthn->value] = $this->hasPasskeys
                ? __('users/profile.preferred_mfa.form.method.options.webauthn_or_passkey')
                : __('users/profile.preferred_mfa.form.method.options.webauthn');
        }

        return $options;
    }

    #[Computed]
    public function hasPasskeys(): bool
    {
        return $this->user->hasPasskeys();
    }

    #[Computed]
    public function showForm(): bool
    {
        return count($this->availableMethods) > 0;
    }

    #[Computed]
    public function user(): User
    {
        return auth()->user();
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    public function render(): string
    {
        return <<<'HTML'
        <div @class(['mt-4' => $this->showForm])>
            @if ($this->showForm)
                <div class="border rounded-md py-3 px-4 dark:border-gray-700">
                    <x-filament-panels::form
                        wire:submit="store"
                        :wire:key="$this->getId() . '.form'"
                    >
                        <dl class="form-group my-0">
                            <dt>
                                <label for="mfa.preference" class="text-sm font-semibold">
                                    {{ __('users/profile.preferred_mfa.title') }}
                                </label>
                            </dt>

                            <dt class="mb-4 mt-1 text-xs">
                                {{ __('users/profile.preferred_mfa.description') }}
                            </dt>

                            <dd id="mfa-pref-wrapper.{{ now()->unix() }}">
                                {{ $this->form }}
                            </dd>
                        </dl>
                    </x-filament-panels::form>
                </div>
            @endif
        </div>
        HTML;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('selectedMethod')
                    ->label('')
                    ->hiddenLabel()
                    ->id('mfa.preference')
                    ->maxWidth(MaxWidth::ExtraSmall)
                    ->required()
                    ->selectablePlaceholder(false)
                    ->live()
                    ->options($this->availableMethods)
                    ->in(array_keys($this->availableMethods)),
            ]);
    }

    #[On(MfaEvent::AppAdded->value)]
    #[On(MfaEvent::AppDeleted->value)]
    #[On(MfaEvent::WebauthnKeyAdded->value)]
    #[On(MfaEvent::WebauthnKeyDeleted->value)]
    #[On(MfaEvent::PasskeyRegistered->value)]
    #[On(MfaEvent::PasskeyDeleted->value)]
    public function refreshForm(): void
    {
        unset($this->availableMethods, $this->hasPasskeys, $this->showForm, $this->user);

        $this->fillForm();
    }

    public function store(): void
    {
        $data = $this->form->getState();

        $this->user->settings()->set(UserSetting::PreferredMfaMethod, $data['selectedMethod']);

        $this->getSuccessNotification()?->send();
    }

    protected function getSuccessNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('users/profile.preferred_mfa.form.success'));
    }

    protected function fillForm(): void
    {
        $this->form->fill([
            'selectedMethod' => $this->user->settings()->get(UserSetting::PreferredMfaMethod),
        ]);
    }
}
