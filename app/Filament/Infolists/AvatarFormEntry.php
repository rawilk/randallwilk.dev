<?php

declare(strict_types=1);

namespace App\Filament\Infolists;

use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class AvatarFormEntry extends ImageEntry
{
    protected string $view = 'filament.infolists.avatar-form-entry';

    protected function setUp(): void
    {
        $this->label(__('users/resource.actions.avatar.label'));

        $this->circular();

        $this->height(150);

        $this->extraImgAttributes(
            fn (User $record): array => [
                'alt' => __('users/resource.actions.avatar.alt', ['name' => $record->name->full]),
            ]
        );

        $this->registerActions([
            $this->uploadAction(),
            $this->removeAction(),
        ]);
    }

    public function avatarActions(User $user): ActionGroup
    {
        $actions = [
            $this->getAction('upload'),
        ];

        if ($user->hasAvatar()) {
            $actions[] = $this->getAction('remove');
        }

        return ActionGroup::make($actions)
            ->button()
            ->size(ActionSize::Small)
            ->icon('heroicon-o-pencil')
            ->color('primary')
            ->label(__('users/resource.actions.avatar.edit.trigger'))
            ->tooltip(null)
            ->extraAttributes([
                'class' => '!px-2.5 !py-1.5',
            ], merge: true);
    }

    public function removeAction(): Action
    {
        return Action::make('remove')
            ->label(__('users/resource.actions.avatar.remove.trigger'))
            ->successNotificationTitle(
                fn (User $record): string => $record->is(auth()->user())
                    ? __('users/resource.actions.avatar.remove.success')
                    : __('users/resource.actions.avatar.remove.success_other_user')
            )
            ->before(function (Action $action, User $record) {
                if (Gate::denies('update', $record)) {
                    $action->cancel();
                }
            })
            ->action(function (User $record, Action $action) {
                $record->deleteAvatar();

                $action->success();
            });
    }

    public function uploadAction(): Action
    {
        return Action::make('upload')
            ->label(__('users/resource.actions.avatar.edit.upload_trigger'))
            ->form([
                FileUpload::make('avatar_path')
                    ->label(__('users/resource.actions.avatar.edit.input_label'))
                    ->hiddenLabel()
                    ->helperText(
                        fn (): Htmlable => new HtmlString(Blade::render(<<<'HTML'
                        <div class="text-center">
                            {{ __('users/resource.actions.avatar.edit.helper_text') }}
                        </div>
                        HTML))
                    )
                    ->disk('avatars')
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->alignCenter()
                    ->required()
                    ->maxSize(1024),
            ])
            ->modalHeading(__('users/resource.actions.avatar.edit.modal_title'))
            ->modalWidth(MaxWidth::Small)
            ->modalSubmitAction(fn (StaticAction $action) => $action->extraAttributes(['class' => 'w-full'])->label(__('users/resource.actions.avatar.edit.submit_button')))
            ->modalCancelAction(fn (StaticAction $action) => $action->extraAttributes(['class' => 'w-full']))
            ->successNotificationTitle(
                fn (User $record): string => $record->is(auth()->user())
                    ? __('users/resource.actions.avatar.edit.success')
                    : __('users/resource.actions.avatar.edit.success_other_user')
            )
            ->before(function (Action $action, User $record) {
                if (Gate::denies('update', $record)) {
                    $action->cancel();
                }
            })
            ->action(function (Form $form, User $record, Action $action) {
                $record->updateAvatar($form->getState()['avatar_path']);

                $action->success();
            });
    }
}
