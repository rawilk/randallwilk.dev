<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Profile;

use App\Livewire\Profile\ProfileInfo as LivewireProfileInfo;
use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo as BasePage;

class ProfileInfo extends BasePage
{
    protected function defaultLivewireComponents(): array
    {
        return [
            LivewireProfileInfo::class,
        ];
    }
}
