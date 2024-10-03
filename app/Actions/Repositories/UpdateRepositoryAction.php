<?php

declare(strict_types=1);

namespace App\Actions\Repositories;

use App\Enums\RepositoryType;
use App\Models\GitHub\Repository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class UpdateRepositoryAction
{
    public function __invoke(Repository $repository, array $input): void
    {
        $data = Validator::make($input, [
            'visible' => ['boolean'],
            'type' => ['required', Rule::enum(RepositoryType::class)],
            'scoped_name' => ['nullable', 'string', 'max:255'],
            'documentation_url' => ['nullable', 'string', 'max:255'],
            'blogpost_url' => ['nullable', 'string', 'max:255'],
            'new' => ['boolean'],
            'highlighted' => ['boolean'],
        ])->validate();

        $repository->forceFill($data)->save();
    }
}
