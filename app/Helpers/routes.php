<?php

declare(strict_types=1);

if (! function_exists('defaultLoginRedirect')) {
    function defaultLoginRedirect(): string
    {
        return auth()->user()?->can('viewAdminPanel')
            ? route('admin.dashboard')
            : route('profile.show');
    }
}

if (! function_exists('homeRoute')) {
    function homeRoute(): string
    {
        return '/';
    }
}
