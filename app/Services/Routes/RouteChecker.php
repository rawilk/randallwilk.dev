<?php

namespace App\Services\Routes;

use Illuminate\Routing\Router as Route;
use Illuminate\Routing\UrlGenerator as URL;
use Illuminate\Support\Str;

/**
 * Route Checker
 *
 * Helper class to determine if a route or url is the currently active page.
 */
class RouteChecker
{
    /**
     * @var \Illuminate\Routing\Router
     */
    protected $route;

    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $url;

    /**
     * Create a new class instance.
     *
     * @param \Illuminate\Routing\Router $route
     * @param \Illuminate\Routing\UrlGenerator $url
     */
    public function __construct(Route $route, URL $url)
    {
        $this->route = $route;
        $this->url = $url;
    }

    /**
     * Determine if the given route name matches the current route name.
     *
     * @example user.*
     *
     * @param string $routeName
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    public function isActiveRoute(string $routeName, $activeClass = 'active')
    {
        if (Str::contains($routeName, '*')) {
            // Quote all RE characters, then undo the quoted '*' characters to match any
            // sequence of non-'.' characters
            $regex = '/^' . str_replace(preg_quote('*'), '[^.]*?', preg_quote($routeName, '/')) . '/';

            if (preg_match($regex, $this->route->currentRouteName())) {
                return $activeClass;
            }
        } elseif ($this->route->currentRouteName() === $routeName) {
            return $activeClass;
        }

        return null;
    }

    /**
     * Determine if the given url matches the current url.
     *
     * @example /users
     *
     * @param string $url
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    public function isActiveUrl(string $url, $activeClass = 'active')
    {
        if ($this->url->current() === $this->url->to($url)) {
            return $activeClass;
        }

        return null;
    }

    /**
     * Determine if the given string is found in the current url.
     *
     * @param string $string
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    public function isActiveMatch(string $string, $activeClass = 'active')
    {
        if (Str::contains($this->url->current(), $string)) {
            return $activeClass;
        }

        return null;
    }

    /**
     * Determine if any of the given route names match the current route name.
     *
     * @param array $routeNames
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    public function areActiveRoutes(array $routeNames, $activeClass = 'active')
    {
        foreach ($routeNames as $routeName) {
            if ($this->isActiveRoute($routeName, true)) {
                return $activeClass;
            }
        }

        return null;
    }

    /**
     * Determine if any of the given urls match the current url.
     *
     * @param array $urls
     * @param string|bool $activeClass
     * @return null|string|bool
     */
    public function areActiveUrls(array $urls, $activeClass = 'active')
    {
        foreach ($urls as $url) {
            if ($this->isActiveUrl($url, true)) {
                return $activeClass;
            }
        }

        return null;
    }
}