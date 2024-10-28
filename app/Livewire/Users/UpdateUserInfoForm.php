<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Arr;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Rawilk\ProfileFilament\Contracts\PendingUserEmail\UpdateUserEmailAction;
use Rawilk\ProfileFilament\Models\PendingUserEmail;

/**
 * @property-read null|PendingUserEmail $pendingEmail
 * @property Form $form
 */
class UpdateUserInfoForm extends Component implements HasForms
{
    use AuthorizesRequests;
    use InteractsWithForms;
    use WithRateLimiting;

    #[Locked]
    public User $user;

    public ?array $data = [];

    #[Computed]
    public function pendingEmail(): ?PendingUserEmail
    {
        return app(config('profile-filament.models.pending_user_email'))::query()
            ->forUser($this->user)
            ->latest()
            ->first();
    }

    public function mount(): void
    {
        $this->form->fill([
            'name' => $this->user->name->full,
            'email' => $this->user->email,
            'timezone' => $this->user->timezone,
            'is_admin' => $this->user->is_admin,
            'avatar_path' => $this->user->avatar_path,
        ]);
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-filament-panels::form
                wire:submit="update"
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
            ->inlineLabel()
            ->schema([
                UserResource::getAvatarField(),
                UserResource::getNameField(),
                UserResource::getEmailField()
                    ->helperText(function (): ?Htmlable {
                        if (! $this->pendingEmail) {
                            return null;
                        }

                        return str(__('users/view.user_info_form.pending_email.change_pending', ['email' => e($this->pendingEmail->email)]))
                            ->inlineMarkdown()
                            ->toHtmlString();
                    })
                    ->hintActions([
                        $this->getResendPendingEmailAction(),
                        $this->getCancelPendingEmailAction(),
                    ])
                    ->unique(
                        table: User::class,
                        ignorable: $this->user,
                    ),
                UserResource::getTimezoneField(),
                UserResource::getIsAdminField()
                    ->disabled(fn (): bool => $this->user->is(auth()->user())),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('submit')
                        ->label(__('filament-actions::edit.single.modal.actions.save.label'))
                        ->color('primary')
                        ->submit('update'),
                ]),
            ]);
    }

    public function update(UpdateUserEmailAction $updateEmailAction): void
    {
        $this->authorize('update', $this->user);

        $data = $this->form->getState();

        $this->user->update(Arr::except($data, ['email', 'avatar_path']));

        $updateEmailAction($this->user, $data['email']);

        if ($data['email'] !== $this->user->email) {
            data_set($this->data, 'email', $this->user->email);
        }

        if ($data['avatar_path'] !== $this->user->avatar_path) {
            $this->user->updateAvatar($data['avatar_path']);
        }

        $this->getSuccessNotification()->send();
    }

    protected function getSuccessNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('filament-actions::edit.single.notifications.saved.title'));
    }

    protected function getResendPendingEmailAction(): Forms\Components\Actions\Action
    {
        return Forms\Components\Actions\Action::make('resend')
            ->label(__('profile-filament::pages/settings.email.actions.resend.trigger'))
            ->color('primary')
            ->visible(fn (): bool => filled($this->pendingEmail))
            ->authorize(fn (): bool => Gate::allows('update', $this->user))
            ->action(function () {
                try {
                    $this->rateLimit(maxAttempts: 3, decaySeconds: 60 * 60, method: 'resendPendingUserEmail');
                } catch (TooManyRequestsException $exception) {
                    Notification::make()
                        ->title(__('profile-filament::pages/settings.email.actions.resend.throttled.title'))
                        ->body(
                            __('profile-filament::pages/settings.email.actions.resend.throttled.body', [
                                'seconds' => $exception->secondsUntilAvailable,
                                'minutes' => ceil($exception->secondsUntilAvailable / 60),
                            ])
                        )
                        ->danger()
                        ->send();

                    return;
                }

                $mailable = config('profile-filament.mail.pending_email_verification');

                Mail::to($this->pendingEmail->email)->send(
                    new $mailable($this->pendingEmail, 'admin')
                );

                Notification::make()
                    ->success()
                    ->title(__('profile-filament::pages/settings.email.actions.resend.success_title'))
                    ->body(__('profile-filament::pages/settings.email.actions.resend.success_body'))
                    ->send();
            });
    }

    protected function getCancelPendingEmailAction(): Forms\Components\Actions\Action
    {
        return Forms\Components\Actions\Action::make('cancel')
            ->label(__('profile-filament::pages/settings.email.actions.cancel.trigger'))
            ->color('danger')
            ->visible(fn (): bool => filled($this->pendingEmail))
            ->action(function () {
                app(config('profile-filament.models.pending_user_email'))::query()
                    ->forUser($this->user)
                    ->delete();

                unset($this->pendingEmail);

                $this->clearRateLimiter('resendPendingUserEmail');
            });
    }
}
