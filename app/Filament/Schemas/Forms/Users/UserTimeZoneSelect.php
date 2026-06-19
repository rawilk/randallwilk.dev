<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Users;

use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect;

use function App\Helpers\appTimezone;

class UserTimeZoneSelect extends TimezoneSelect
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('users/resource.form.timezone.label'));

        $this->validationAttribute(__('timezone'));

        $this->searchable();

        $this->optionsLimit(100);

        $this->selectablePlaceholder(false);

        $this->default(fn (): string => appTimezone());

        $this->required();
    }

    public static function getDefaultName(): ?string
    {
        return 'timezone';
    }
}
