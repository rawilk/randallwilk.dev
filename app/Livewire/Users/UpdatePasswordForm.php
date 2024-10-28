<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Rawilk\FilamentPasswordInput\Password;
use Rawilk\ProfileFilament\Concerns\Sudo\UsesSudoChallengeAction;
use Rawilk\ProfileFilament\Events\UserPasswordWasUpdated;

/**
 * @property Form $form
 */
class UpdatePasswordForm extends Component implements HasActions, HasForms
{
    use AuthorizesRequests;
    use InteractsWithActions;
    use InteractsWithForms;
    use UsesSudoChallengeAction;

    public ?array $data = [];

    #[Locked]
    public User $user;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-filament-panels::form
                wire:submit="update"
                :wire:key="$this->getId() . '.forms.data'"
            >
                {{ $this->form }}
            </x-filament-panels::form>
        </div>
        HTML;
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->operation('edit')
            ->model($this->user)
            ->schema([
                Password::make('password')
                    ->label(__('users/view.password.form.password.label'))
                    ->required()
                    ->copyable()
                    ->rules([
                        PasswordRule::defaults(),
                    ])
                    ->regeneratePassword()
                    ->inlineSuffix(),

                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('submit')
                        ->label(__('filament-actions::edit.single.modal.actions.save.label'))
                        ->color('primary')
                        ->action('update'),
                ]),
            ]);
    }

    #[On('sudo-active')]
    public function update(): void
    {
        $this->authorize('update', $this->user);

        if (! $this->ensureSudoIsActive()) {
            return;
        }

        $data = $this->form->getState();

        $this->user->update(['password' => $data['password']]);

        UserPasswordWasUpdated::dispatch($this->user);

        $this->getSuccessNotification()->send();

        $this->form->fill();
    }

    protected function getSuccessNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('filament-actions::edit.single.notifications.saved.title'));
    }
}
