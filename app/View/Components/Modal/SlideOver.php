<?php

declare(strict_types=1);

namespace App\View\Components\Modal;

use App\View\Components\BladeComponent;

class SlideOver extends BladeComponent
{
    protected bool $isTenantComponent = true;

    public function __construct(
        public null|string $id = null,
        public bool $wide = true,
        public null|string $header = null,
        public null|string $footer = null,
        public bool $showClose = true,
    ) {}

    public function id(): null|string
    {
        return $this->id ?? md5((string) $this->attributes->wire('model'));
    }
}
