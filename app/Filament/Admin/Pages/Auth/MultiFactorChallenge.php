<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Auth;

use App\Filament\Concerns\Auth\IsAuthPage;
use Rawilk\ProfileFilament\Auth\Multifactor\Filament\MultiFactorChallenge as BasePage;

class MultiFactorChallenge extends BasePage
{
    use IsAuthPage;

    protected static string $layout = 'layouts.auth.base';
}
