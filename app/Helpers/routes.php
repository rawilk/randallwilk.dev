<?php

declare(strict_types=1);

namespace App\Helpers;

function defaultLoginRedirect(): string
{
    return auth()->user()?->can('viewAdminPanel')
        ? route('admin.dashboard')
        : route('profile.show');
}

function homeRoute(): string
{
    if (auth()->check() && auth()->user()->isSuperAdmin()) {
        return route('admin.dashboard');
    }

    return '/';
}
