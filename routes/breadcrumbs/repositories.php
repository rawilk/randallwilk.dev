<?php

use App\Models\GitHub\Repository;
use Rawilk\Breadcrumbs\Facades\Breadcrumbs;
use Rawilk\Breadcrumbs\Support\Generator;

Breadcrumbs::for(
    'admin.repositories.index',
    fn (Generator $trail) => $trail->push(__('repos.title'), route('admin.repositories.index'))
);

Breadcrumbs::for(
    'admin.repositories.show',
    fn (Generator $trail, Repository $repository) => $trail->parent('admin.repositories.index')->push($repository->name, $repository->show_url)
);
