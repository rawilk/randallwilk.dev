<?php

use Illuminate\Support\HtmlString;

function renderSvg(string $filename): HtmlString
{
    return new HtmlString(
        file_get_contents(resource_path("svg/{$filename}.svg"))
    );
}

if (! function_exists('isExternalLink')) {
    function isExternalLink(string $url): bool
    {
        $parsed = parse_url($url);
        $parsedSiteUrl = parse_url(config('app.url'));

        return ($parsed['host'] ?? '') !== ($parsedSiteUrl['host'] ?? '');
    }
}

if (! function_exists('defaultLoginRedirect')) {
    function defaultLoginRedirect(): string
    {
        return auth()->user()?->is_admin
            ? route('admin.dashboard')
            : route('profile.show');
    }
}

if (! function_exists('appTimezone')) {
    function appTimezone(): string {
        return config('site.timezone');
    }
}

if (! function_exists('userTimezone')) {
    function userTimezone(): string
    {
        return auth()->user()?->timezone ?? appTimezone();
    }
}

if (! function_exists('formatPageTitle')) {
    function formatPageTitle(...$segments): string
    {
        return collect($segments)
            ->flatten()
            ->implode(' | ');
    }
}

if (! function_exists('isImpersonating')) {
    function isImpersonating(): bool
    {
        return session()->has('impersonate');
    }
}
