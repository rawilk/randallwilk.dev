<?php

declare(strict_types=1);

namespace App\Actions\Repositories;

use App\Models\GitHub\Repository;

final class DeleteRepositoryAction
{
    public function __invoke(Repository $repository): void
    {
        $repository->delete();
    }
}
