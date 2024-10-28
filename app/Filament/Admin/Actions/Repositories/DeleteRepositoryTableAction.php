<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Repositories;

use Filament\Tables\Actions\DeleteAction;

class DeleteRepositoryTableAction extends DeleteAction
{
    use Concerns\DeletesRepositories;
}
