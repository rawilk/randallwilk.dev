<?php

use Rawilk\Breadcrumbs\Facades\Breadcrumbs;
use Rawilk\Breadcrumbs\Support\Generator;

Breadcrumbs::for(
    'admin.roles.index',
    fn (Generator $trail) => $trail->push(__('base::roles.index.title'), route('admin.roles.index'))
);

Breadcrumbs::for(
    'admin.roles.create',
    fn (Generator $trail) => $trail->parent('admin.roles.index')
        ->push(__('base::roles.create.title'), route('admin.roles.create'))
);

Breadcrumbs::for(
    'admin.roles.edit',
    fn (Generator $trail, $role) => $trail->parent('admin.roles.index')
        ->push(__('base::roles.edit.title'), route('admin.roles.edit', $role))
);
