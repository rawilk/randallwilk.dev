<?php

use App\Models\Repository;
use App\Models\User;
use Rawilk\Breadcrumbs\Facades\Breadcrumbs;
use Rawilk\Breadcrumbs\Support\Generator;

// Repositories...
Breadcrumbs::for(
    'admin.repositories',
    fn (Generator $trail) => $trail->push(__('repositories.page_title'), route('admin.repositories'))
);

Breadcrumbs::for(
    'admin.repositories.show',
    fn (Generator $trail, Repository $repository)
        => $trail->parent('admin.repositories')->push($repository->name, $repository->show_url)
);

// Users...
Breadcrumbs::for(
    'admin.users',
    fn (Generator $trail) => $trail->push(__('users.page_title'), route('admin.users'))
);

Breadcrumbs::for(
    'admin.users.create',
    fn (Generator $trail)
        => $trail->parent('admin.users')->push(__('users.form.create_title'), route('admin.users.create'))
);

Breadcrumbs::for(
    'admin.users.edit',
    fn (Generator $trail, User $user)
        => $trail
            ->parent('admin.users')
            ->push($user->name->full, $user->edit_url)
);

// User profile...
Breadcrumbs::for(
    'profile.show',
    fn (Generator $trail) => $trail->push(__('users.profile.account_info.page_title'), route('profile.show'))
);

Breadcrumbs::for(
    'profile.authentication',
    fn (Generator $trail) =>
        $trail->parent('profile.show')
            ->push(__('users.profile.authentication.page_title'), route('profile.authentication'))
);

// Frontend Docs...
Breadcrumbs::for(
    'docs.repository',
    function (Generator $trail, $repository, $alias, $page) {
        $trail->push('Docs', route('docs'))
            ->push($repository->slug, action([\App\Http\Controllers\DocsController::class, 'repository'], [$repository->slug, $alias->slug]));

        if (! $page->isRootPage()) {
            $trail->push(ucfirst($page->section), '#');
        }

        $trail->push($page->title, '#');
    }
);
