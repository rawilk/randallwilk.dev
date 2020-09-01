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
