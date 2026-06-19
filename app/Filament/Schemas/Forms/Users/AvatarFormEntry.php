<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Users;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class AvatarFormEntry extends ImageEntry
{
    protected string $view = 'filament.schemas.forms.users.avatar-form-entry';

    protected function setUp(): void
    {
        $this->label(__('users/resource.actions.avatar.label'));

        $this->circular();

        $this->imageHeight(150);

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
            ->size(Size::Small)
            ->icon('heroicon-o-pencil')
            ->color('primary')
            ->label(__('users/resource.actions.avatar.edit.trigger'))
            ->tooltip(null)
            ->extraAttributes([
                'class' => 'px-2.5! py-1.5!',
            ], merge: true);
    }

    public function removeAction(): Action
    {
        return Action::make('remove')
            ->label(__('users/resource.actions.avatar.remove.trigger'))
            ->successNotificationTitle(
                fn (User $record): string => $record->is(Filament::auth()->user())
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
            ->schema([
                FileUpload::make('avatar_path')
                    ->label(__('users/resource.actions.avatar.edit.input_label'))
                    ->hiddenLabel()
                    ->belowContent(
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
            ->modalWidth(Width::Small)
            ->modalSubmitAction(fn (Action $action) => $action->extraAttributes(['class' => 'w-full'])->label(__('users/resource.actions.avatar.edit.submit_button')))
            ->modalCancelAction(fn (Action $action) => $action->extraAttributes(['class' => 'w-full']))
            ->successNotificationTitle(
                fn (User $record): string => $record->is(Filament::auth()->user())
                    ? __('users/resource.actions.avatar.edit.success')
                    : __('users/resource.actions.avatar.edit.success_other_user')
            )
            ->before(function (Action $action, User $record) {
                if (Gate::denies('update', $record)) {
                    $action->cancel();
                }
            })
            ->action(function (array $data, User $record, Action $action) {
                $record->updateAvatar($data['avatar_path']);

                $action->success();
            });
    }
}
