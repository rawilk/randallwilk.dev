<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Livewire\Component;

final class ProfileNavigation extends Component
{
    protected $listeners = [
        'refresh-profile-navigation' => '$refresh',
    ];

    public string $viewName = 'layouts.admin.partials.profile-navigation';

    public function render(): View
    {
        return view($this->viewName);
    }
}
