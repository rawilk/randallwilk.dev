<?php

declare(strict_types=1);

namespace App\View\Components\Modal;

use App\View\Components\BladeComponent;

class SlideOverForm extends BladeComponent
{
    protected bool $isTenantComponent = true;

    public function __construct(
        public null | string $id = null,
        public bool $wide = true,
        public null | string $title = null,
        public null | string $subTitle = null,
        public null | string $footer = null
    ) {
    }
}
