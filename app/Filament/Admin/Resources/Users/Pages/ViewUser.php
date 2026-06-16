<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Schemas\Infolists\Users\ViewUserInfolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

use function App\Helpers\formatPageTitle;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getHeading(): string|Htmlable
    {
        return $this->getRecord()->name->full;
    }

    public function getTitle(): string|Htmlable
    {
        return formatPageTitle(
            $this->getRecord()->name->full,
            static::getResource()::getBreadcrumb(),
        );
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema(ViewUserInfolist::make());
    }
}
