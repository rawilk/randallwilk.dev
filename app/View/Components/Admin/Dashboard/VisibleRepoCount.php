<?php

declare(strict_types=1);

namespace App\View\Components\Admin\Dashboard;

use App\Models\GitHub\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

final class VisibleRepoCount extends Component
{
    public int $count = 0;

    public function __construct()
    {
        $this->count = Cache::remember(
            'repos.visible_count',
            now()->addWeek(),
            fn () => Repository::visible()->whereNotNull('type')->count(),
        );
    }

    public function render(): string
    {
        return <<<'HTML'
        <x-admin.dashboard.count-widget
            icon="css-git-branch"
            :title="__('Visible Repositories')"
            :count="$count"
            :url="route('admin.repositories.index')"
        />
        HTML;
    }
}
