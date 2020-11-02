<?php

declare(strict_types=1);

namespace App\View\Components\Auth;

use App\View\Components\BladeComponent;

class AuthenticationForm extends BladeComponent
{
    public string $title;
    public string $subTitle;

    public function __construct(string $title = '', string $subTitle = '')
    {
        $this->title = $title;
        $this->subTitle = $subTitle;
    }
}
