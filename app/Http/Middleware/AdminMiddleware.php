<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * @deprecated Will remove soon
 */
class AdminMiddleware
{
    /*
     * Certain "admin" routes need to always be accessible
     * for all users, such as the route for ending
     * an impersonation.
     */
    private static array $except = [
        'admin.impersonate.leave' => 'DELETE',
    ];

    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldAllowThrough($request)) {
            return $next($request);
        }

        if ($request->user()?->can('viewAdminPanel')) {
            return $next($request);
        }

        abort(SymfonyResponse::HTTP_NOT_FOUND);
    }

    private function shouldAllowThrough(Request $request): bool
    {
        return rescue(function () use ($request) {
            $exception = self::$except[$request->route()?->getName()] ?? null;

            return $exception === $request->getMethod();
        }, false);
    }
}
