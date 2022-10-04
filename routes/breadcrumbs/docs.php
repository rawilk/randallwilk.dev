<?php

use App\Http\Controllers\Docs\DocsController;
use Rawilk\Breadcrumbs\Facades\Breadcrumbs;
use Rawilk\Breadcrumbs\Support\Generator;

Breadcrumbs::for(
    'docs.repository',
    function (Generator $trail, $repository, $alias, $page, $sectionTitle = null) {
        $trail->push(__('front.menus.main.docs'), route('docs'))
            ->push($repository->slug, action([DocsController::class, 'repository'], [$repository->slug, $alias->slug]));

        if ($sectionTitle && ! $page->isRootPage()) {
            $trail->push($sectionTitle, '#');
        }

        $trail->push($page->title, '#');
    }
);
