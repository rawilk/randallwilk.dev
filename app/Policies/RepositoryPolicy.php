<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class RepositoryPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability)
    {
        if ($ability === 'importDocs') {
            return null;
        }

        if ($user->isAdmin()) {
            return Response::allow();
        }
    }

    public function update(User $user, Repository $repository)
    {
    }

    public function delete(User $user, Repository $repository)
    {
    }

    public function restore(User $user, Repository $repository)
    {
    }

    public function deleteAny(User $user)
    {
    }

    public function restoreAny(User $user)
    {
    }

    public function view(User $user, Repository $repository)
    {
    }

    public function viewAny(User $user)
    {
    }

    public function manage(User $user)
    {
    }

    public function importDocs(User $user, Repository $repository): Response
    {
        if (Gate::denies('manage', $repository::class)) {
            return Response::deny();
        }

        $repositoriesWithDocs = collect(config('docs.repositories'))->keyBy('repository');

        return Arr::has($repositoriesWithDocs, $repository->full_name)
            ? Response::allow()
            : Response::deny();
    }
}
