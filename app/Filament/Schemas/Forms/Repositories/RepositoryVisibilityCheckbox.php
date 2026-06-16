<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Repositories;

use Filament\Forms\Components\Checkbox;

class RepositoryVisibilityCheckbox extends Checkbox
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('repositories/resource.form.visible.label'));

        $this->belowContent(__('repositories/resource.form.visible.help'));
    }

    public static function getDefaultName(): ?string
    {
        return 'visible';
    }
}
