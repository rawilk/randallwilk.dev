<?php

declare(strict_types=1);

namespace App\Filament\Schemas;

use Closure;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Stringable;

class CopyableText extends TextEntry
{
    protected bool|Closure $isMonoSpaced = false;

    protected bool|Closure $isOneLine = false;

    protected string|Htmlable|Closure|null $copyTooltip = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(function (mixed $state): ?Htmlable {
            $state = $this->normalizeCopyableState($state);

            if (blank($state)) {
                return null;
            }

            return new HtmlString(Blade::render(<<<'HTML'
                <x-text.copyable-text
                    :text="$text"
                    :mono="$mono"
                    :tooltip="$tooltip"
                    :clamp="$clamp"
                    bordered
                />
                HTML, [
                'text' => $state,
                'mono' => $this->shouldUseMonospace(),
                'tooltip' => $this->getCopyTooltip(),
                'clamp' => $this->shouldClampToOneLine(),
            ]));
        });
    }

    public function oneLine(bool|Closure $condition = true): static
    {
        $this->isOneLine = $condition;

        return $this;
    }

    public function clamp(bool|Closure $condition = true): static
    {
        return $this->oneLine($condition);
    }

    public function clampToOneLine(bool|Closure $condition = true): static
    {
        return $this->oneLine($condition);
    }

    public function fontMono(bool|Closure $condition = true): static
    {
        $this->isMonoSpaced = $condition;

        return $this;
    }

    public function mono(bool|Closure $condition = true): static
    {
        return $this->fontMono($condition);
    }

    public function useMonoSpace(bool|Closure $condition = true): static
    {
        return $this->fontMono($condition);
    }

    public function copyTooltip(string|Htmlable|Closure|null $tooltip): static
    {
        $this->copyTooltip = $tooltip;

        return $this;
    }

    public function getCopyTooltip(): string|Htmlable
    {
        return $this->evaluate($this->copyTooltip) ?? __('actions.copyable_text.tooltip');
    }

    public function shouldUseMonospace(): bool
    {
        return (bool) $this->evaluate($this->isMonoSpaced);
    }

    public function shouldClampToOneLine(): bool
    {
        return (bool) $this->evaluate($this->isOneLine);
    }

    protected function normalizeCopyableState(mixed $state): ?string
    {
        if ($state instanceof Htmlable) {
            return $state->toHtml();
        }

        if ($state instanceof Stringable) {
            return (string) $state;
        }

        if (is_scalar($state)) {
            return (string) $state;
        }

        if ($state === null) {
            return null;
        }

        return json_encode($state) ?: null;
    }
}
