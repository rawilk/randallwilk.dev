<?php

declare(strict_types=1);

namespace App\View\Components\Elements;

use App\View\Components\BladeComponent;

final class Card extends BladeComponent
{
    public function __construct(
        public bool $flush = false, // Set to true to remove padding from the body
        public $header = false,
        public $footer = false,
        public bool $roundedOnMobile = true,
    ) {}
}
