<?php

declare(strict_types=1);

namespace App\View\Components\Front;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class ContentArea extends Component
{
    public function __construct(
        public bool $indentLists = true,
        public bool $largeText = true,
        // Mainly for legal pages, for headings to act as <li> elements.
        public bool $headingsAsBullets = false,
        public bool $wrap = true, // Apply "wrap" class for padding
    ) {
    }

    public function render(): View
    {
        return view('components.front.content-area');
    }
}
