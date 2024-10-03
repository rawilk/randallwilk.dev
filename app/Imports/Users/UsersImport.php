<?php

declare(strict_types=1);

namespace App\imports\Users;

use App\Actions\Users\CreateUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Enums\PermissionEnum;
use App\Models\Access\Role;
use App\Models\User\User;
use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Imports\GeneralImport;

final class UsersImport extends GeneralImport
{
    protected function processChunk(array $chunk): void
    {
        foreach ($chunk as $record) {
            $this->processRecord($record);
        }

        $this->incrementProcessedByChunkSize();
    }

    private function processRecord(array $record): void
    {
        if (empty($record['email'])) {
            return;
        }

        if ($this->canUpdateOrCreate()) {
            $this->updateOrCreate($record);

            return;
        }

        if ($this->canCreate()) {
            $this->createOnly($record);

            return;
        }

        if ($this->canUpdate()) {
            $this->updateOnly($record);
        }
    }

    private function createOnly(array $record): void
    {
        ['permissions' => $permissions, 'roles' => $roles, 'data' => $data] = $this->normalizeData($record);

        if (User::withoutGlobalScopes()->where('email', $data['email'])->exists()) {
            return;
        }

        rescue(function () use ($permissions, $roles, $data) {
            app(CreateUserAction::class)(
                array_merge($data, compact('roles', 'permissions')),
                $this->user,
                true,
            );
        });
    }

    private function updateOnly(array $record): void
    {
        ['permissions' => $permissions, 'roles' => $roles, 'data' => $data] = $this->normalizeData($record);

        rescue(function () use ($permissions, $roles, $data) {
            $user = User::withoutGlobalScopes()->where('email', $data['email'])->firstOrFail();

            app(UpdateUserAction::class)(
                $user,
                array_merge($data, compact('permissions', 'roles')),
                $this->user,
            );
        });
    }

    private function updateOrCreate(array $record): void
    {
        ['permissions' => $permissions, 'roles' => $roles, 'data' => $data] = $this->normalizeData($record);

        $user = User::withoutGlobalScopes()->firstOrNew(['email' => $data['email']]);

        if ($user->getKey()) {
            rescue(function () use ($user, $permissions, $roles, $data) {
                app(UpdateUserAction::class)(
                    $user,
                    array_merge($data, compact('roles', 'permissions')),
                    $this->user,
                );
            });

            return;
        }

        rescue(function () use ($permissions, $roles, $data) {
            app(CreateUserAction::class)(
                array_merge($data, compact('roles', 'permissions')),
                $this->user,
                true,
            );
        });
    }

    private function canUpdateOrCreate(): bool
    {
        if (! $this->user) {
            return true;
        }

        return $this->user->hasAllPermissions([PermissionEnum::USERS_CREATE->value, PermissionEnum::USERS_EDIT->value]);
    }

    private function canCreate(): bool
    {
        if (! $this->user) {
            return true;
        }

        return $this->user->can(PermissionEnum::USERS_CREATE->value);
    }

    private function canUpdate(): bool
    {
        if (! $this->user) {
            return true;
        }

        return $this->user->can(PermissionEnum::USERS_EDIT->value);
    }

    private function normalizeData(array $data): array
    {
        $permissions = Arr::get($data, 'permissions');
        $roles = Arr::get($data, 'roles');

        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true);
        }

        if (is_string($roles)) {
            $roles = json_decode($roles, true);
        }

        $data['name'] = trim(
            $data['first_name'] . ' ' . ($data['last_name'] ?? '')
        );

        $otherExceptions = empty($data['password'] ?? '') ? ['password'] : [];

        return [
            'permissions' => $this->normalizePermissions($roles, $permissions),
            'roles' => $roles,
            'data' => Arr::except($data, ['avatar_path', 'permissions', 'roles', ...$otherExceptions]),
        ];
    }

    private function normalizePermissions(mixed $roles, mixed $permissions): ?array
    {
        if (! is_array($permissions)) {
            return null;
        }

        if (! is_array($roles)) {
            return $permissions;
        }

        if (in_array(Role::$superAdminName, $roles, true)) {
            return [];
        }

        return $permissions;
    }
}
