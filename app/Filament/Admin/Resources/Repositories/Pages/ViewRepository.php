<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Repositories\Pages;

use App\Filament\Admin\Actions\Repositories\DeleteRepositoryAction;
use App\Filament\Admin\Actions\Repositories\ImportRepositoryDocsAction;
use App\Filament\Admin\Actions\Repositories\SyncRepositoryInfoAction;
use App\Filament\Admin\Resources\Repositories\RepositoryResource;
use App\Filament\Schemas\Infolists\Repositories\ViewRepositoryInfolist;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

use function App\Helpers\formatPageTitle;

class ViewRepository extends ViewRecord
{
    protected static string $resource = RepositoryResource::class;

    public function getHeading(): string|Htmlable
    {
        return new HtmlString(Blade::render(<<<'HTML'
        <div class="flex items-center gap-x-3">
            <span>{{ $record->name }}</span>

            <x-filament::badge
                :color="$record->visible ? 'success' : 'danger'"
                class="mt-1"
            >
                {{
                    $record->visible
                        ? __('repositories/view.attributes.visible.true')
                        : __('repositories/view.attributes.visible.false')
                }}
            </x-filament::badge>
        </div>
        HTML, [
            'record' => $this->getRecord(),
        ]));
    }

    public function getTitle(): string|Htmlable
    {
        return formatPageTitle(
            $this->getRecord()->name,
            static::getResource()::getBreadcrumb(),
        );
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema(ViewRepositoryInfolist::make());
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                EditAction::make(),

                ActionGroup::make([
                    SyncRepositoryInfoAction::make(),
                    ImportRepositoryDocsAction::make(),
                ])->dropdown(false),

                ActionGroup::make([
                    DeleteRepositoryAction::make(),
                    RestoreAction::make()
                        ->modalIconColor('primary'),
                ])->dropdown(false),
            ])->pageHeader(),
        ];
    }
}
