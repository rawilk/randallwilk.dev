<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class BladeComponent extends Component
{
    public function render()
    {
        $alias = Str::kebab(class_basename($this));

        $config = Config::get("app-blade-components.components.{$alias}");

        return view($config['view']);
    }
}
