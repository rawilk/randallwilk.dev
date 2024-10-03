<?php

declare(strict_types=1);

namespace App\Actions\Repositories;

use App\Models\Repository;

final class ToggleVisibilityAction
{
    public function __invoke(Repository $repository): void
    {
        $repository->visible = ! $repository->visible;
        $repository->save();
    }
}
