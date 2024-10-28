<?php

declare(strict_types=1);

namespace App\Filament\Infolists;

use Closure;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class CopyableTextEntry extends TextEntry
{
    protected null|bool|Closure $isMonoSpaced = false;

    protected null|bool|Closure $clampedToOneLine = false;

    protected null|string|Htmlable|Closure $copyTooltip = null;

    protected bool|Closure $isBordered = false;

    protected function setUp(): void
    {
        $this->extraAttributes(fn () => [
            'class' => $this->shouldBeBordered() ? $this->getBorderedContainerClasses() : '',
        ], merge: true);

        $this->formatStateUsing(function (?string $state): ?Htmlable {
            if (blank($state)) {
                return null;
            }

            return new HtmlString(Blade::render(<<<'HTML'
            <x-text.copyable-text
                :text="$text"
                :mono="$mono"
                :tooltip="$tooltip"
                :clamp="$clamp"
                :bordered="$bordered"
            />
            HTML, [
                'text' => $state,
                'mono' => $this->shouldUseMonospace(),
                'tooltip' => $this->getCopyTooltip(),
                'clamp' => $this->shouldLineClamp(),
                'bordered' => $this->shouldBeBordered(),
            ]));
        });
    }

    public function useMonoSpace(null|bool|Closure $condition = true): static
    {
        $this->isMonoSpaced = $condition;

        return $this;
    }

    public function oneLine(null|bool|Closure $condition = true): static
    {
        $this->clampedToOneLine = $condition;

        return $this;
    }

    public function copyTooltip(null|string|Htmlable $tooltip): static
    {
        $this->copyTooltip = $tooltip;

        return $this;
    }

    public function bordered(bool|Closure $condition = true): static
    {
        $this->isBordered = $condition;

        return $this;
    }

    public function getCopyTooltip(): string|Htmlable
    {
        return $this->evaluate($this->copyTooltip) ?? __('actions.copyable_text.tooltip');
    }

    public function shouldUseMonospace(): bool
    {
        return $this->evaluate($this->isMonoSpaced) ?? false;
    }

    public function shouldLineClamp(): bool
    {
        return $this->evaluate($this->clampedToOneLine) ?? false;
    }

    public function shouldBeBordered(): bool
    {
        return $this->evaluate($this->isBordered);
    }

    protected function getBorderedContainerClasses(): string
    {
        return implode(' ', [
            '[&_.fi-in-affixes>div>div>div]:w-full',
            '[&_.fi-in-affixes>div>div>div]:max-w-none',
            '[&_.fi-in-affixes>div>div>div_div]:w-full',
        ]);
    }
}
