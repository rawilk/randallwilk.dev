<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Repositories\Concerns;

use App\Models\Repository;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

trait DeletesRepositories
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'));

        $this->modalDescription(
            fn (Repository $record): Htmlable => new HtmlString(Blade::render(<<<'HTML'
            <x-filament.modal-description align="center" balance-text :pretty-text="false">
                {{
                    str(__('repositories/resource.actions.delete.modal_description', ['name' => e($record->name)]))
                        ->markdown()
                        ->toHtmlString()
                }}
            </x-filament.modal-description>
            HTML, ['record' => $record])),
        );
    }
}
