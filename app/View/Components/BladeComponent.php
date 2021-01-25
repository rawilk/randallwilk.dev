<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class BladeComponent extends Component
{
    public function render(): View
    {
        $config = Config::get("app-blade-components.components.{$this->alias()}");

        return view($config['view']);
    }

    protected function alias(): string
    {
        return Str::kebab(class_basename($this));
    }
}
