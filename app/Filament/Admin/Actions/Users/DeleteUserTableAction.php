<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Users;

use Filament\Tables\Actions\DeleteAction;

class DeleteUserTableAction extends DeleteAction
{
    use Concerns\DeletesUsers;
}
