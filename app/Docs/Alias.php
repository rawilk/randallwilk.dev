<?php

declare(strict_types=1);

namespace App\Docs;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Alias
{
    protected ?Collection $navigation = null;

    public function __construct(
        public string $slug,
        public string $slogan,
        public string $branch,
        public int $versionNumber,
        public string $githubUrl,
        public Collection $pages,
    ) {
    }

    public static function fromDocumentationPage(DocumentationPage $page, Collection $pages): static
    {
        return new static(
            slug: $page->title,
            slogan: $page->slogan,
            branch: $page->branch,
            versionNumber: (int) filter_var($page->title, FILTER_SANITIZE_NUMBER_INT),
            githubUrl: $page->githubUrl,
            pages: $pages,
        );
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

        return $flattenedArrayOfPages[$currentIndex + 1] ?? null;
    }

    public function previousPage(): ?DocumentationPage
    {
        if (! $flattenedArrayOfPages = $this->getFlattenedArrayOfPages()) {
            return null;
        }

        $pathsByIndex = $flattenedArrayOfPages->pluck('url');

        $currentIndex = $pathsByIndex->search(request()->url());

        return $flattenedArrayOfPages[$currentIndex - 1] ?? null;
    }

    public function branchUrl(): string
    {
        return "{$this->githubUrl}/tree/{$this->branch}";
    }

    public function versionSelectAlias(): string
    {
        return Str::of((string) $this->versionNumber)
            ->append('.x')
            ->toString();
    }

    protected function getFlattenedArrayOfPages(): ?Collection
    {
        return $this->navigation?->map(fn ($item) => $item['pages'] ?? [])->flatten(1);
    }
}
