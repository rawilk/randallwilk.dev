<?php

use App\Services\Routes\RouteChecker;

/**
 * Generate an appropriate canonical link if necessary.
 *
 * @param string|null $canonical
 * @param mixed $exception
 * @return string
 */
function getCanonicalLink(?string $canonical = null, $exception = null) : string
{
    if ($canonical !== null && $canonical !== '') {
        return $canonical;
    }

    try {
        if ($exception !== null && method_exists($exception, 'getStatusCode') && $exception->getStatusCode() === 404) {
            return url($exception->getStatusCode());
        }
    } catch (\Exception $e) {}

    return request()->url();
}

if (! function_exists('includeRouteFiles')) {
    /**
     * Require all route files in the given directory.
     * Searches sub-directories as well.
     *
     * @param string $dir
     */
    function includeRouteFiles($dir)
    {
        try {
            $rdi = new recursiveDirectoryIterator($dir);
            $it = new recursiveIteratorIterator($rdi);
            while ($it->valid()) {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }
                $it->next();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (! function_exists('isActiveRoute')) {
    /**
     * Determine if the given route name matches the current route name.
     *
     * @example user.*
     *
     * @param string $routeName
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    function isActiveRoute(string $routeName, $activeClass = 'active')
    {
        return app(RouteChecker::class)->isActiveRoute($routeName, $activeClass);
    }
}

if (! function_exists('isActiveUrl')) {
    /**
     * Determine if the given url matches the current url.
     *
     * @example /users
     *
     * @param string $url
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    function isActiveUrl(string $url, $activeClass = 'active')
    {
        return app(RouteChecker::class)->isActiveUrl($url, $activeClass);
    }
}

if (! function_exists('isActiveMatch')) {
    /**
     * Determine if the given string is found in the current url.
     *
     * @param string $string
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    function isActiveMatch(string $string, $activeClass = 'active')
    {
        return app(RouteChecker::class)->isActiveMatch($string, $activeClass);
    }
}

if (! function_exists('areActiveRoutes')) {
    /**
     * Determine if any of the given route names match the current route name.
     *
     * @param array $routeNames
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    function areActiveRoutes(array $routeNames, $activeClass = 'active')
    {
        return app(RouteChecker::class)->areActiveRoutes($routeNames, $activeClass);
    }
}

if (! function_exists('areActiveUrls')) {
    /**
     * Determine if any of the given urls match the current url.
     *
     * @param array $urls
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    function areActiveUrls(array $urls, $activeClass = 'active') {
        return app(RouteChecker::class)->areActiveUrls($urls, $activeClass);
    }
}