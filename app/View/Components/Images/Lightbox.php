<?php

declare(strict_types=1);

namespace App\View\Components\Images;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Lightbox extends Component
{
    public function __construct(
        public readonly string $src,
        public readonly string $alt,
    ) {
    }

    public function render(): View
    {
        return view('components.images.lightbox');
    }
}
