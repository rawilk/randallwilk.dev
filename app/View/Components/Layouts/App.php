<?php

declare(strict_types=1);

namespace App\View\Components\Layouts;

use App\View\Components\BladeComponent;
use Illuminate\Support\Facades\Config;

class App extends BladeComponent
{
    protected string $title;

    public function __construct(string $title = '')
    {
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title
            ? "{$this->title} | " . Config::get('app.name')
            : Config::get('app.name');
    }
}
