<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Users\Concerns;

use App\Actions\Users\DeleteUserAction;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

trait DeletesUsers
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'));

        $this->modalDescription(function (User $record) {
            return new HtmlString(Blade::render(<<<'HTML'
            <x-filament.modal-description>
                {{ str($description)->markdown()->toHtmlString() }}
            </x-filament.modal-description>
            HTML, [
                'description' => __('users/resource.actions.delete.modal_description', ['name' => e($record->name->full)]),
            ]));
        });

        $this->using(function (User $record, DeleteUserAction $deleter) {
            $deleter($record);

            return true;
        });
    }
}
