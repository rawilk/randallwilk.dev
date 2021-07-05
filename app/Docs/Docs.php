<?php

namespace App\Docs;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use RuntimeException;
use Spatie\Sheets\Sheets;

class Docs
{
    private Collection $pages;

    public function __construct(Sheets $sheets)
    {
        $pages = Cache::store('docs')->get('docs');

        if (is_null($pages)) {
            throw new RuntimeException('Docs cache is invalid.');
        }

        $this->pages = $pages;
    }

    public function pages(): Collection
    {
        return $this->pages;
    }

    public function getRepository(string $slug): Repository
    {
        return $this->getRepositories()->firstWhere('slug', $slug);
    }

    public function getRepositories(): Collection
    {
        return $this->pages
            ->pluck('repository')
            ->unique()
            ->filter()
            ->map(function (string $repository) {
                $aliases = $this->pages
                    ->where('repository', $repository)
                    ->whereNotNull('alias')
                    ->groupBy(fn (DocumentationPage $page) => $page->alias)
                    ->map(static function (Collection $pages) use ($repository) {
                        $index = $pages->firstWhere('slug', '_index');
                        $pages = $pages
                            ->where('slug', '<>', '_index')
                            ->sortBy(fn (DocumentationPage $page) => $page->sort ?? PHP_INT_MAX);

                        return new Alias(
                            $index->title,
                            $index->slogan,
                            $index->branch,
                            $index->githubUrl,
                            $pages,
                            $repository,
                        );
                    })
                    ->sortBy('slug');

                $index = $this->pages
                    ->where('repository', $repository)
                    ->whereNull('alias')
                    ->firstWhere('slug', '_index');

                return new Repository($repository, $aliases, $index);
            })
            ->sortBy('slug');
    }
}
