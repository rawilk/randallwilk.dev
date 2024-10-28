<?php

declare(strict_types=1);

namespace App\Filament\Concerns\Auth;

trait IsAuthPage
{
    public function mountIsAuthPage(): void
    {
        $this->setLogoHeight();
    }

    public function hydrateIsAuthPage(): void
    {
        $this->setLogoHeight();
    }

    protected function setLogoHeight(): void
    {
        filament()->getCurrentPanel()->brandLogoHeight('2.5rem');
    }
}
