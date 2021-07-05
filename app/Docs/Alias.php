<?php

namespace App\Docs;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class Alias
{
    protected null|Collection $navigation = null;
    public string $mainBranchName;

    public function __construct(
        public string $slug,
        public string $slogan,
        public string $branch,
        public string $githubUrl,
        public Collection $pages,
        public null|string $repository = null,
    ) {
        $this->mainBranchName = $this->getMainBranchName();
    }

    public function isMainBranch(): bool
    {
        return $this->branch === $this->mainBranchName;
    }

    private function getMainBranchName(): string
    {
        $config = collect(Config::get('docs.repositories'))
            ->where('name', $this->repository)
            ->first();

        return Arr::get($config, 'main_branch', 'main');
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
