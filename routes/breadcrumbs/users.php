<?php

use App\Models\User\User;
use Rawilk\Breadcrumbs\Facades\Breadcrumbs;
use Rawilk\Breadcrumbs\Support\Generator;

Breadcrumbs::for(
    'admin.users.index',
    fn (Generator $trail) => $trail->push(__('base::users.index.title'), route('admin.users.index'))
);

Breadcrumbs::for(
    'admin.users.create',
    fn (Generator $trail) => $trail->parent('admin.users.index')->push(__('Create User'), route('admin.users.create'))
);

Breadcrumbs::for(
    'admin.users.edit',
    fn (Generator $trail, User $user) => $trail
        ->parent('admin.users.index')
        ->push($user->name->full, $user->edit_url)
);

Breadcrumbs::for(
    'admin.users.edit.abilities',
    fn (Generator $trail, User $user) => $trail
        ->parent('admin.users.edit', $user)
        ->push(__('users.abilities.page_title'), $user->abilities_url)
);
