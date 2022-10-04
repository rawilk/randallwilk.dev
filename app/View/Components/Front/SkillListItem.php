<?php

declare(strict_types=1);

namespace App\View\Components\Front;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

final class SkillListItem extends Component
{
    public ?string $url;

    public ?string $description;

    public function __construct(public string $skill, public array $data = [])
    {
        $this->url = Arr::get($data, 'url');
        $this->description = Arr::get($data, 'description');
    }

    public function render(): View
    {
        return view('components.front.skill-list-item');
    }
}
