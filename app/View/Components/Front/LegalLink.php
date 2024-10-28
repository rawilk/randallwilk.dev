<?php

declare(strict_types=1);

namespace App\View\Components\Front;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\Component;

/**
 * This component is mostly here to skirt around my IDE formatting my html weirdly sometimes, and causing
 * spaces between a link and a period or comma, for example.
 */
class LegalLink extends Component
{
    public function __construct(public string $url = '#', public ?string $text = null, public string $after = '', public string $target = '_self')
    {
        $this->text = $this->text ?: $this->url;
    }

    public function render(): string|Htmlable
    {
        if ($this->target === '_blank') {
            return "<a href=\"{$this->url}\" target=\"_blank\" rel=\"nofollow noopener\">{$this->text}</a>{$this->after}";
        }

        return str("[{$this->text}]({$this->url}){$this->after}")
            ->inlineMarkdown()
            ->toHtmlString();
    }
}
