<?php

declare(strict_types=1);

namespace App\Docs;

use Illuminate\Support\Str;
use Spatie\Sheets\Sheet;

/**
 * @property string $alias
 * @property string $branch
 * @property null|string $category
 * @property string $githubUrl
 * @property string $repository
 * @property string $section
 * @property string $slogan
 * @property string $slug
 * @property string $title
 * @property null|string $url
 */
class DocumentationPage extends Sheet
{
    public function isIndex(): bool
    {
        return Str::endsWith($this->slug, '_index');
    }

    public function versionSelectAlias(): string
    {
        return Str::of($this->alias)
            ->replace('v', '')
            ->append('.x')
            ->toString();
    }

    public function isRootPage(): bool
    {
        return $this->section === '_root';
    }

    public function getSectionAttribute(): string
    {
        $parts = explode('/', $this->slug);

        if (count($parts) === 1) {
            return '_root';
        }

        return $parts[0];
    }

    public function getUrlAttribute(): ?string
    {
        return route('docs.show', [
            'repository' => $this->repository,
            'alias' => $this->alias,
            'slug' => $this->slug,
        ]);
    }

    public function editUrl(): string
    {
        return "{$this->githubUrl}/blob/{$this->branch}/docs/{$this->slug}.md";
    }
}
