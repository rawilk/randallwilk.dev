<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->can('viewAdminPanel')) {
            return $next($request);
        }

        abort(Response::HTTP_NOT_FOUND);
    }
}
