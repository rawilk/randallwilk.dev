<?php

namespace App\Http\Controllers;

use App\Docs\Docs;
use App\Docs\DocumentationPage;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class DocsController
{
    public function index(Docs $docs)
    {
        return view('front.pages.docs.index', [
            'repositories' => $docs->getRepositories(),
        ]);
    }

    public function repository(string $repository, ?string $alias = null, Docs $docs): \Illuminate\Http\RedirectResponse
    {
        $repository = $docs->getRepository($repository);

        abort_unless((bool) $repository, Response::HTTP_NOT_FOUND, 'Repository not found');

        if ($alias) {
            $alias = $repository->getAlias($alias);

            abort_if(is_null($alias), Response::HTTP_NOT_FOUND, 'Version not found');
        } else {
            $alias = $repository->aliases->first();
        }

        return redirect()->action([__CLASS__, 'show'], [
            $repository->slug,
            $alias->slug,
            $alias->pages->where('section', '_root')->first()->slug,
        ]);
    }

    public function show(string $repository, string $alias, string $slug, Docs $docs)
    {
        $repository = $docs->getRepository($repository);

        abort_if(is_null($repository), Response::HTTP_NOT_FOUND, 'Repository not found');

        $alias = $repository->getAlias($alias);

        abort_if(is_null($alias), Response::HTTP_NOT_FOUND, 'Version not found');

        /** @var Collection $pages */
        $pages = $alias->pages;

        $page = $pages->firstWhere('slug', $slug);

        if (! $page) {
            return redirect()->action([__CLASS__, 'repository'], [$repository->slug, $alias->slug]);
        }

        $repositories = $docs->getRepositories();

        $navigation = $this->getNavigation($pages);
        $alias->setNavigation($navigation);

        $showBigTitle = $page->slug === $navigation['_root']['pages'][0]->slug;

        return view('front.pages.docs.show', compact(
            'page',
            'repositories',
            'repository',
            'pages',
            'navigation',
            'alias',
            'showBigTitle'
        ));
    }

    private function getNavigation(Collection $pages): Collection
    {
        $navigation = $pages
            ->reduce(static function (array $navigation, DocumentationPage $page) {
                if ($page->isIndex()) {
                    $navigation[$page->section]['_index'] = $page;
                } else {
                    $navigation[$page->section]['pages'][] = $page;
                }

                return $navigation;
            }, []);

        return collect($navigation)
            ->sortBy(fn (array $pages) => $pages['_index']->sort ?? -1);
    }
}
