<?php

declare(strict_types=1);

namespace App\Support\Database\Redaction;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserRedactor extends BaseRedactor
{
    protected function query(): Builder
    {
        return User::query()->withoutGlobalScopes();
    }

    protected function redactRecord(Model $record): Model
    {
        return tap($record, function (User $user) {
            $user->fill([
                'two_factor_enabled' => false,
                'two_factor_recovery_codes' => null,
                'avatar_path' => null,
                'remember_token' => null,
            ]);

            if ($this->isExemptFromRedaction($user)) {
                return;
            }

            $user->fill([
                'name' => trim($user->name->first . ' ' . ($this->maskValue($user->name->last) ?? '')),
                'email' => $this->maskEmail($user->email),
                'password' => Str::password(),
                'github_id' => null,
                'github_username' => null,
            ]);
        });
    }

    protected function isExemptFromRedaction(User $user): bool
    {
        return in_array($user->email, $this->usersToIgnore(), true);
    }

    private function usersToIgnore(): array
    {
        return once(fn () => config('randallwilk.staging.ignore_users') ?? []);
    }
}
