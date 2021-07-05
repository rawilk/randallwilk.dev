<?php

declare(strict_types=1);

namespace App\View\Components\Modal;

use App\View\Components\BladeComponent;

class DialogModal extends BladeComponent
{
    public function __construct(
        public null|string $id = null,
        public null|string $maxWidth = null,
        public bool $showClose = true,
        public bool $showIcon = true,
    ) {}
}
