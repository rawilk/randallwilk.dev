<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Filament\Schemas\Forms\Users\UserInfoForm;
use App\Models\User;
use Arr;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component as FilamentComponent;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Rawilk\ProfileFilament\Auth\Sudo\Livewire\Concerns\NeedsSudoChallengeAction;
use Rawilk\ProfileFilament\Contracts\PendingUserEmail\UpdateUserEmailAction;
use Rawilk\ProfileFilament\Models\PendingUserEmail;
use Rawilk\ProfileFilament\Support\Config;

/**
 * @property-read null|PendingUserEmail $pendingEmail
 * @property Schema $form
 */
class UpdateUserInfoForm extends Component implements HasActions, HasSchemas
{
    use AuthorizesRequests;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use NeedsSudoChallengeAction;

    public User $user;

    public ?array $data = [];

    #[Computed]
    public function pendingEmail(): ?PendingUserEmail
    {
        return app(Config::getModel('pending_user_email'))::query()
            ->forUser($this->user)
            ->latest()
            ->first();
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $this->content }}

            <x-filament-actions::modals />
        </div>
        HTML;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->formContentComponent(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->operation('edit')
            ->record($this->user)
            ->components(
                fn (): array => UserInfoForm::make(fieldsOnly: true, operation: 'edit', pendingUserEmail: $this->pendingEmail),
            );
    }

    public function update(UpdateUserEmailAction $updateEmailAction): void
    {
        $this->authorize('update', $this->user);

        $data = $this->form->getState();

        $this->user->update(Arr::except($data, ['email', 'avatar_path']));

        if (Arr::has($data, 'email')) {
            $updateEmailAction($this->user, $data['email']);

            if ($data['email'] !== $this->user->email) {
                data_set($this->data, 'email', $this->user->email);

                unset($this->pendingEmail);

                $this->fillForm();

                $this->js('$wire.$refresh');
            }
        }

        if ($data['avatar_path'] !== $this->user->avatar_path) {
            $this->user->updateAvatar($data['avatar_path']);
        }

        $this->getSuccessNotification()->send();
    }

    protected function formContentComponent(): FilamentComponent
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->key($this->getId() . '.forms.data')
            ->livewireSubmitHandler('update')
            ->footer([
                Actions::make([
                    Action::make('submit')
                        ->label(__('filament-actions::edit.single.modal.actions.save.label'))
                        ->submit('update'),
                ]),
            ]);
    }

    protected function getSuccessNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('filament-actions::edit.single.notifications.saved.title'));
    }

    protected function fillForm(): void
    {
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'timezone' => $this->user->timezone,
            'is_admin' => $this->user->is_admin,
            'avatar_path' => $this->user->avatar_path,
        ]);
    }
}
