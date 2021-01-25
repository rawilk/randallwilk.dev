<?php

declare(strict_types=1);

namespace App\View\Components\Admin\Layout;

use App\View\Components\BladeComponent;

final class PageTitle extends BladeComponent
{
    public function __construct(
        public bool|string|null $breadcrumbs = null,
        public string $actions = '',
        public array $breadcrumbParams = [],
    ) {}

    protected function alias(): string
    {
        return 'admin-page-title';
    }
}
