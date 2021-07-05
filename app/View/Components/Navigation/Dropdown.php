<?php

declare(strict_types=1);

namespace App\View\Components\Navigation;

use App\Services\Popper\Popper;
use App\View\Components\BladeComponent;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

final class Dropdown extends BladeComponent
{
    private null|string $triggerId = null;
    private null|string $menuId = null;

    public function __construct(
        public bool $right = false,
        public bool $withBackground = false,
        public null|string $triggerText = null,
        public bool $splitButton = false,
        public string $buttonVariant = 'white',
        public bool $disabled = false,
        public null|string $size = null,
        public string $widthClass = 'w-56',
        public null|string $placement = null,
        public bool $dropUp = false,
        public string|int $offset = 8,
        public bool $fixed = false,
        public null|string|bool $id = null,
    ) {
        if ($this->id !== false) {
            $this->id = $this->id ?? Str::random(8);
        }
    }

    public function config(): array
    {
        return [
            'disabled' => $this->disabled,
            'offset' => $this->offset,
            'placement' => $this->getPlacement(),
            'fixed' => $this->fixed,
        ];
    }

    public function getPlacement(): string
    {
        if (! is_null($this->placement)) {
            return $this->placement;
        }

        return match(true) {
            $this->dropUp && $this->right => Popper::PLACEMENT_TOP_RIGHT,
            $this->dropUp && ! $this->right => Popper::PLACEMENT_TOP_LEFT,
            ! $this->dropUp && $this->right => Popper::PLACEMENT_BOTTOM_RIGHT,
            default => Popper::PLACEMENT_BOTTOM_LEFT,
        };
    }

    public function triggerId(): string
    {
        if (! is_null($this->triggerId)) {
            return $this->triggerId;
        }

        return $this->triggerId = 'dropdown-' . $this->id;
    }

    public function menuId(): string
    {
        if (! is_null($this->menuId)) {
            return $this->menuId;
        }

        return $this->menuId = 'dropdown-menu-' . $this->id;
    }

    public function triggerAttributes(): HtmlString
    {
        return new HtmlString(implode(PHP_EOL, array_filter([
            'x-ref="trigger"',
            'x-on:click="toggleMenu"',
            'aria-haspopup="true"',
            'x-bind:aria-expanded="JSON.stringify(open)"',
            $this->id === false ? null : 'id="' . $this->triggerId() . '"',
            $this->id === false ? null : 'aria-controls="' . $this->menuId() . '"',
        ])));
    }

    public function configToJson(): string
    {
        return '...' . json_encode((object) $this->config()) . ',';
    }
}
