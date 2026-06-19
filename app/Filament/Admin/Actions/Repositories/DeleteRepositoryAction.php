<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Repositories;

use App\Models\Repository;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class DeleteRepositoryAction extends DeleteAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalAlignment(Alignment::Left);

        $this->modalDescription(
            fn (Repository $record): Htmlable => new HtmlString(Blade::render(<<<'HTML'
            <div class="fi-modal-description text-pretty">
                {{
                    str(__('repositories/resource.actions.delete.modal_description', ['name' => e($record->name)]))
                        ->markdown()
                        ->toHtmlString()
                }}
            </div>
            HTML, ['record' => $record])),
        );
    }
}
