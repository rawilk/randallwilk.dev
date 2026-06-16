<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Repositories;

use App\Enums\RepositoryType;
use Filament\Forms\Components\Select;

class RepositoryTypeSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('repositories/resource.form.type.label'));

        $this->options(RepositoryType::class);

        $this->native(false);

        $this->selectablePlaceholder(false);

        $this->required();

        $this->enum(RepositoryType::class);
    }

    public static function getDefaultName(): ?string
    {
        return 'type';
    }
}
