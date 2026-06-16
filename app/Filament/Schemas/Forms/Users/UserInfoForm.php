<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Users;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;
use Rawilk\ProfileFilament\Filament\Actions\Emails\CancelPendingEmailChangeAction;
use Rawilk\ProfileFilament\Filament\Actions\Emails\ResendPendingEmailAction;
use Rawilk\ProfileFilament\Models\PendingUserEmail;

class UserInfoForm
{
    public static function make(
        bool $fieldsOnly = false,
        string $operation = 'create',
        ?PendingUserEmail $pendingUserEmail = null,
    ): array {
        $wrapper = $fieldsOnly
            ? Group::make([])
            : Section::make(__('User info'));

        return [
            $wrapper
                ->schema(fn (?User $record): array => [
                    AvatarFileUpload::make(),

                    NameInput::make()
                        ->hintAction(
                            Action::make('fill')
                                ->label(__('Fill form'))
                                ->color('warning')
                                ->visible(fn (string $operation): bool => $operation === 'create' && (! app()->isProduction()))
                                ->action(function (Set $set): void {
                                    $set('name', 'Dexter Morgan');
                                    $set('email', Str::random() . '@example.test');
                                    $set('timezone', 'America/Chicago');
                                }),
                        ),

                    UserEmailInput::make()
                        ->when(
                            fn (): bool => $operation === 'edit',
                            fn (UserEmailInput $component) => $component
                                ->belowContent(function () use ($pendingUserEmail) {
                                    if (! $pendingUserEmail) {
                                        return null;
                                    }

                                    return str(__('users/view.user_info_form.pending_email.change_pending', ['email' => e($pendingUserEmail->email)]))
                                        ->inlineMarkdown()
                                        ->toHtmlString();
                                })
                                ->hintActions([
                                    ResendPendingEmailAction::make()
                                        ->record($pendingUserEmail)
                                        ->visible(filled($pendingUserEmail))
                                        ->authorize('update', $record),

                                    CancelPendingEmailChangeAction::make()
                                        ->color('danger')
                                        ->record($pendingUserEmail)
                                        ->authorize('update', $record)
                                        ->visible(filled($pendingUserEmail))
                                        ->using(function (PendingUserEmail $pendingUserEmail, CancelPendingEmailChangeAction $action) use ($record): void {
                                            $pendingUserEmail->delete();

                                            RateLimiter::clear($action->rateLimitKey($record));
                                        })
                                        ->after(function (Component $livewire) {
                                            $livewire->js('$wire.$refresh');
                                        }),
                                ]),
                        )
                        // For security, force admin users to update their own email address via profile, so they
                        // are forced to use sudo mode to edit it.
                        ->disabled(
                            fn (): bool => $operation === 'edit' && Filament::auth()->user()->is($record),
                        ),

                    UserTimeZoneSelect::make(),

                    Checkbox::make('is_admin')
                        ->label(__('users/resource.form.is_admin.label'))
                        ->belowContent(__('users/resource.form.is_admin.help'))
                        ->disabled(fn (): bool => Filament::auth()->user()->is($record)),
                ]),
        ];
    }
}
