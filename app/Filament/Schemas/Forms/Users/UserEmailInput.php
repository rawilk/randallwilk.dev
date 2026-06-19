<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Users;

use App\Models\User;
use App\Rules\UniqueEmail;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class UserEmailInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('users/resource.form.email.label'));

        $this->placeholder(__('users/resource.form.email.placeholder'));

        $this->validationAttribute(__('email'));

        $this->required();

        $this->email();

        $this->maxLength(255);

        $this->when(
            app()->isProduction(),
            fn (TextInput $component) => $component->rule('email:rfc,dns')
        );

        $this->dehydrateStateUsing(fn (string $state): string => Str::lower($state));

        $this->rule(
            fn (?User $record = null): UniqueEmail => UniqueEmail::make()->withUser($record),
        );
    }

    public static function getDefaultName(): ?string
    {
        return 'email';
    }
}
