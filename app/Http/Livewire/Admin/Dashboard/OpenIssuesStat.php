<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Dashboard;

use App\Models\Issue;
use Livewire\Component;

final class OpenIssuesStat extends Component
{
    public string $url = 'https://github.com/issues?q=is%3Aopen+is%3Aissue+user%3Arawilk+archived%3Afalse';
    public int $issuesCount;

    public function mount(): void
    {
        $this->issuesCount = Issue::count();
    }
}
