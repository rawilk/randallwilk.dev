<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\RepositoryResource\Pages;

use App\Filament\Admin\Resources\RepositoryResource;
use App\Models\Repository;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;

/**
 * @property-read bool $canManage
 */
class ListRepositories extends ListRecords
{
    protected static string $resource = RepositoryResource::class;

    #[Computed]
    public function canManage(): bool
    {
        return Gate::allows('manage', Repository::class);
    }

    public function getTabs(): array
    {
        return [
            'not-trashed' => Tab::make()
                ->label(__('repositories/resource.tabs.not_trashed.label'))
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()),
            'trashed' => Tab::make()
                ->label(__('repositories/resource.tabs.trashed.label'))
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }
}
