<?php

namespace App\Actions\Concerns;

use App\Rules\Password;

trait PasswordValidationRules
{
    protected function passwordRules(bool $needsConfirm = true, bool $optional = false): array
    {
        return [$optional ? 'nullable' : 'required', 'string', new Password, $needsConfirm ? 'confirmed' : null];
    }
}
