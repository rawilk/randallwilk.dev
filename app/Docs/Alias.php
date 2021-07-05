<?php

namespace App\Docs;

use Illuminate\Support\Collection;

class Alias
{
    public string $slug;
    public string $slogan;
    public string $branch;
    public string $githubUrl;

    /** @var \Illuminate\Support\Collection|\App\Docs\DocumentationPage[] */
    public Collection $pages;

    protected ?Collection $navigation = null;

    public function __construct(string $slug, string $slogan, string $branch, string $githubUrl, Collection $pages)
    {
        $this->slug = $slug;
        $this->slogan = $slogan;
        $this->branch = $branch;
        $this->githubUrl = $githubUrl;
        $this->pages = $pages;
    }

    public function isMasterBranch(): bool
    {
        return $this->branch === 'master'
            || $this->branch === 'main';
    }

    public function setNavigation(Collection $navigation): void
    {
        $this->navigation = $navigation;
    }

    public function nextPage(): ?DocumentationPage
    {
        if (! $flattenedArrayOfPages = $this->getFlattenedArrayOfPages()) {
            return null;
        }

        $pathsByIndex = $flattenedArrayOfPages->pluck('url');

        $currentIndex = $pathsByIndex->search(request()->url());

        $nextIndex = $currentIndex + 1;

        return $flattenedArrayOfPages[$nextIndex] ?? null;
    }

    public function previousPage(): ?DocumentationPage
    {
        if (! $flattenedArrayOfPages = $this->getFlattenedArrayOfPages()) {
            return null;
        }

        $pathsByIndex = $flattenedArrayOfPages->pluck('url');

        $currentIndex = $pathsByIndex->search(request()->url());

        $previousIndex = $currentIndex - 1;

        return $flattenedArrayOfPages[$previousIndex] ?? null;
    }

    protected function getFlattenedArrayOfPages(): ?Collection
    {
        if (! $this->navigation) {
            return null;
        }

        return $this->navigation
            ->map(fn ($item) => $item['pages'] ?? [])
            ->flatten(1);
    }
}
