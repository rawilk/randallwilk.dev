<?php

declare(strict_types=1);

use Rawilk\Breadcrumbs\Facades\Breadcrumbs;
use Rawilk\Breadcrumbs\Support\Generator;

Breadcrumbs::for(
    'profile.show',
    fn (Generator $trail) => $trail->push(__('users.profile.account.page_title'), route('profile.show'))
);

Breadcrumbs::for(
    'profile.authentication',
    fn (Generator $trail) => $trail->parent('profile.show')->push(__('users.profile.authentication.title'), route('profile.authentication'))
);
