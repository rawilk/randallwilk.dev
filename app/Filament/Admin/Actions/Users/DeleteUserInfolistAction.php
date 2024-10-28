<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Users;

use App\Actions\Users\DeleteUserAction;
use App\Models\User;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Infolists\Components\Actions\Action;
use Filament\Support\Facades\FilamentIcon;

class DeleteUserInfolistAction extends Action
{
    use CanCustomizeProcess;
    use Concerns\DeletesUsers {
        Concerns\DeletesUsers::setUp as traitSetUp;
    }

    protected function setUp(): void
    {
        $this->requiresConfirmation();

        $this->traitSetUp();

        $this->label(__('filament-actions::delete.single.label'));

        $this->color('danger');

        $this->modalIcon(FilamentIcon::resolve('actions::delete-action.modal') ?? 'heroicon-o-trash');

        $this->successNotificationTitle(__('filament-actions::delete.single.notifications.deleted.title'));

        $this->action(function () {
            $result = $this->process(function (DeleteUserAction $deleter, User $record) {
                $deleter($record);

                return true;
            });

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });

        $this->keyBindings(['mod+d']);
    }

    public static function getDefaultName(): ?string
    {
        return 'delete';
    }
}
