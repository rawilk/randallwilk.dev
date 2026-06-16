<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Repositories;

use App\Filament\Schemas\Forms\Repositories\BulkEditRepositoryFieldGroup;
use App\Filament\Schemas\Forms\Repositories\RepositoryTypeSelect;
use App\Filament\Schemas\Forms\Repositories\RepositoryVisibilityCheckbox;
use App\Models\Repository;
use Filament\Actions\BulkAction;
use Filament\Actions\View\ActionsIconAlias;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class BulkEditRepositoriesAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('repositories/resource.actions.edit_bulk.label'));

        $this->modalHeading(__('repositories/resource.actions.edit_bulk.modal_heading'));

        $this->icon(FilamentIcon::resolve(ActionsIconAlias::EDIT_ACTION) ?? Heroicon::PencilSquare);

        $this->modalSubmitActionLabel(__('filament-actions::edit.single.modal.actions.save.label'));

        $this->successNotificationTitle(__('filament-actions::edit.single.notifications.saved.title'));

        $this->modalWidth(Width::SevenExtraLarge);

        $this->authorize(fn (): bool => Gate::allows('manage', Repository::class));

        $this->deselectRecordsAfterCompletion();

        $this->schema([
            BulkEditRepositoryFieldGroup::make(
                RepositoryTypeSelect::make()
                    ->required(fn (Get $get): bool => $get('type_status') === 'edit'),
            ),

            BulkEditRepositoryFieldGroup::make(
                RepositoryVisibilityCheckbox::make(),
            ),
        ]);

        $this->action(function (array $data, Collection $records): void {
            $data = collect($data)
                ->filter(
                    fn (mixed $value, string $key) => (! Str::endsWith($key, '_status'))
                        && data_get($data, "{$key}_status") === 'edit',
                )
                ->all();

            if (empty($data)) {
                return;
            }

            Repository::query()
                ->withTrashed()
                ->whereKey($records->pluck('id')->all())
                ->update($data);
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'bulkEditRepositories';
    }
}
