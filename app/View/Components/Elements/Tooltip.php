<?php

declare(strict_types=1);

namespace App\View\Components\Elements;

use App\View\Components\BladeComponent;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

final class Tooltip extends BladeComponent
{
    // Placements
    public const TOP = 'top';
    public const BOTTOM = 'bottom';
    public const LEFT = 'left';
    public const RIGHT = 'right';

    // Triggers
    public const TRIGGER_CLICK = 'click';
    public const TRIGGER_HOVER = 'hover';
    public const TRIGGER_FOCUS = 'focus';

    public function __construct(
        public string $placement = self::TOP,
        public null|string $title = '',
        private string|array|Collection $triggers = [self::TRIGGER_HOVER, self::TRIGGER_FOCUS],
    )
    {
        if (is_string($this->triggers)) {
            $this->triggers = Arr::wrap($this->triggers);
        }

        if (! $this->triggers instanceof Collection) {
            $this->triggers = collect($this->triggers);
        }
    }

    public function triggerEventListeners(): HtmlString
    {
        $hasHover = $this->hasHoverTrigger();
        $hasClick = $this->hasClickTrigger();
        $hasFocus = $this->hasFocusTrigger();

        $triggers = collect([
            $hasHover ? 'x-on:mouseenter="show"' : null,
            $hasHover ? 'x-on:mouseleave="hide"' : null,
            $hasClick ? 'x-on:click="toggle"' : null,
            $hasFocus ? 'x-on:focusin="show"' : null,
            $hasFocus ? 'x-on:focusout="hide"' : null,
        ])->filter();

        return new HtmlString($triggers->implode(PHP_EOL));
    }

    private function hasHoverTrigger(): bool
    {
        return $this->triggers->contains(self::TRIGGER_HOVER);
    }

    private function hasClickTrigger(): bool
    {
        return $this->triggers->contains(self::TRIGGER_CLICK);
    }

    private function hasFocusTrigger(): bool
    {
        return $this->triggers->contains(self::TRIGGER_FOCUS);
    }
}
