<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Admin\Resources\RepositoryResource;
use App\Models\Repository;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $stats = [];

        if (Gate::allows('manage', Repository::class)) {
            $stats[] = BaseWidget\Stat::make(
                __('repositories/resource.widgets.visible_repos.label'),
                Number::format($this->getVisibleReposCount()),
            )
                ->icon('phosphor-git-branch-duotone')
                ->url(RepositoryResource::getUrl(panel: 'admin'));
        }

        return $stats;
    }

    protected function getVisibleReposCount(): int
    {
        return (int) cache()->remember(
            'repos.visible_count',
            now()->addWeek(),
            fn () => Repository::visible()->whereNotNull('type')->count(),
        );
    }
}
