<?php

declare(strict_types=1);

namespace App\View\Components\Front;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class SkillListItem extends Component
{
    public readonly ?string $url;

    public readonly ?string $description;

    public function __construct(public readonly string $skill, array $data = [])
    {
        $this->url = Arr::get($data, 'url');
        $this->description = Arr::get($data, 'description');
    }

    public function render(): View
    {
        return view('components.front.skill-list-item');
    }
}
