<?php

declare(strict_types=1);

namespace App\Filament\Infolists;

use Closure;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Support\Concerns\HasDescription;
use Illuminate\Contracts\Support\Htmlable;

class ActionListItem extends Entry
{
    use HasDescription;

    protected string $view = 'filament.infolists.action-list-item';

    protected TextEntrySize|string|Closure|null $size = null;

    protected string|Closure|Htmlable|null $content = null;

    protected bool|Closure $contentBeforeDescription = true;

    public function size(TextEntrySize|string|Closure|null $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function withContent(string|Closure|Htmlable|null $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function placeContentBeforeDescription(bool|Closure $condition = true): static
    {
        $this->contentBeforeDescription = $condition;

        return $this;
    }

    public function getContent(): string|Htmlable|null
    {
        return $this->evaluate($this->content);
    }

    public function getSize(): TextEntrySize|string|null
    {
        return $this->evaluate($this->size);
    }

    public function isContentBeforeDescription(): bool
    {
        return $this->evaluate($this->contentBeforeDescription);
    }
}
