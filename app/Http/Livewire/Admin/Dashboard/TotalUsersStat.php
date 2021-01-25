<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Dashboard;

use App\Models\User;
use Livewire\Component;

final class TotalUsersStat extends Component
{
    public int $totalUsers;

    public function mount(): void
    {
        $this->totalUsers = User::count();
    }
}
