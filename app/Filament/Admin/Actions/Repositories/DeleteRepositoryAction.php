<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Repositories;

use Filament\Actions\DeleteAction;

class DeleteRepositoryAction extends DeleteAction
{
    use Concerns\DeletesRepositories;
}
