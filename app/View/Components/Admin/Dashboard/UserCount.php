<?php

declare(strict_types=1);

namespace App\View\Components\Admin\Dashboard;

use App\Models\User\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

final class UserCount extends Component
{
    public int $count = 0;

    public function __construct()
    {
        $this->count = Cache::remember(
            'users.count',
            now()->addWeek(),
            fn () => User::count(),
        );
    }

    public function render(): string
    {
        return <<<'HTML'
        <x-admin.dashboard.count-widget
            icon="heroicon-o-user-group"
            title="{{ __('Total Users') }}"
            :count="$count"
            url="{!! route('admin.users.index') !!}"
        />
        HTML;
    }
}
