<?php

declare(strict_types=1);

namespace App\View\Components\Docs;

use App\Docs\DocHeaders\DocHeaderFactory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DocHeroCode extends Component
{
    /** @var class-string<\App\Docs\DocHeaders\DocHeader>|null */
    public ?string $config;

    public function __construct(public string $repository, public string $version)
    {
        $this->config = DocHeaderFactory::resolve($this->repository);
    }

    public function tabs(): array
    {
        return $this->config::heroTabs($this->version);
    }

    public function codeSnippet(): string
    {
        return $this->config::snippet($this->version);
    }

    public function codeLanguage(): string
    {
        return $this->config::snippetLanguage($this->version);
    }

    public function hasHeroCode(): bool
    {
        return filled($this->config);
    }

    public function render(): View
    {
        return view('components.docs.doc-hero-code');
    }
}
