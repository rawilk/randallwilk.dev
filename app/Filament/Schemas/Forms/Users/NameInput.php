<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Users;

use Filament\Forms\Components\TextInput;
use Rawilk\LaravelCasters\Support\Name;

class NameInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('users/resource.form.name.label'));

        $this->placeholder(__('users/resource.form.name.placeholder'));

        $this->validationAttribute(__('name'));

        $this->required();

        $this->maxLength(255);

        $this->afterStateHydrated(function (null|string|Name $state): void {
            if ($state instanceof Name) {
                $this->state($state->full);
            }
        });

        $this->dehydrateStateUsing(fn (?string $state): ?Name => filled($state) ? Name::from($state) : null);
    }

    public static function getDefaultName(): ?string
    {
        return 'name';
    }
}
