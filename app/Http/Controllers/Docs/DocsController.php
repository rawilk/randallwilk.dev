<?php

declare(strict_types=1);

namespace App\Http\Controllers\Docs;

use App\Docs\Docs;
use App\Docs\DocumentationPage;
use App\Support\CommonMark\TableOfContentsBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

final class DocsController
{
    public function index(Docs $docs): View
    {
        return view('front.pages.docs.index', [
            'repositories' => $docs->getRepositories(),
        ]);
    }

    public function repository(Docs $docs, string $repository, ?string $alias = null)
    {
        try {
            $repository = $docs->getRepository($repository);
        } catch (RuntimeException) {
            abort(Response::HTTP_NOT_FOUND, __('front.docs.alerts.repo_not_found'));
        }

        abort_if(is_null($repository), Response::HTTP_NOT_FOUND, __('front.docs.alerts.repo_not_found'));

        if ($alias) {
            preg_match('/v\d+/', $alias, $matches);

            if (! count($matches)) {
                $latest = $repository->aliases->keys()->first();
                $slug = $alias;
                $alias = $latest;

                return redirect()->action([DocsController::class, 'show'], [$repository->slug, $alias, $slug]);
            }

            $alias = $repository->getAlias($alias);

            abort_if(is_null($alias), Response::HTTP_NOT_FOUND, __('front.docs.alerts.alias_not_found'));
        } else {
            $alias = $repository->aliases->first();
        }

        return redirect()->action([DocsController::class, 'show'], [
            $repository->slug,
            $alias->slug,
            $alias->pages->where('section', '_root')->first()->slug,
        ]);
    }

    public function show(string $repository, string $alias, string $slug, Docs $docs)
    {
        try {
            $repository = $docs->getRepository($repository);
        } catch (RuntimeException) {
            abort(Response::HTTP_NOT_FOUND, __('front.docs.alerts.repo_not_found'));
        }

        abort_if(is_null($repository), Response::HTTP_NOT_FOUND, __('front.docs.alerts.repo_not_found'));

        preg_match('/v\d+/', $alias, $matches);

        if (! count($matches)) {
            $latest = $repository->aliases->keys()->first();
            $slug = "{$alias}/{$slug}";
            $alias = $latest;

            return redirect()->action([DocsController::class, 'show'], [$repository->slug, $alias, $slug]);
        }

        $alias = $repository->getAlias($alias);

        if (! $alias) {
            $alias = $repository->aliases->keys()->first();

            return redirect()->action([DocsController::class, 'show'], [$repository->slug, $alias, $slug]);
        }

        $pages = $alias->pages;

        $page = $pages->firstWhere('slug', $slug);

        if (! $page) {
            return redirect()->action([DocsController::class, 'repository'], [$repository->slug, $alias->slug]);
        }

        $repositories = $docs->getRepositories();

        $navigation = $this->getNavigation($pages);
        $alias->setNavigation($navigation);

        $showBigTitle = $page->slug === $navigation['_root']['pages'][0]->slug;

        $page->contents = Str::replace(
            ['%7Bversion%7D', '%7Bbranch%7D', '{version}', '{branch}'],
            [$alias->slug, $alias->branch, $alias->slug, $alias->branch],
            (string) $page->contents,
        );

        $tableOfContents = $this->extractTableOfContents((string) $page->contents);

        $sectionTitle = $this->getSectionTitle($page->section, $navigation);

        return view('front.pages.docs.show', compact(
            'page',
            'repositories',
            'repository',
            'pages',
            'navigation',
            'alias',
            'showBigTitle',
            'tableOfContents',
            'sectionTitle',
        ));
    }

    private function getNavigation(Collection $pages): Collection
    {
        $navigation = $pages
            ->reduce(function (array $navigation, DocumentationPage $page) {
                if ($page->isIndex()) {
                    $navigation[$page->section]['_index'] = $page;
                } else {
                    $navigation[$page->section]['pages'][] = $page;
                }

                return $navigation;
            }, []);

        return collect($navigation)->sortBy(fn (array $pages) => $pages['_index']->sort ?? -1);
    }

    private function getSectionTitle(string $section, Collection $navigation): ?string
    {
        if ($section === '_root') {
            return 'Introduction';
        }

        $section = $navigation[$section] ?? null;
        if (! $section || ! isset($section['_index'])) {
            return null;
        }

        return $section['_index']['title'] ?? null;
    }

    private function extractTableOfContents(string $contents): array
    {
        return TableOfContentsBuilder::generate($contents);
    }
}
