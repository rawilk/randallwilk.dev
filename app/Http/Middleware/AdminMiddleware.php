<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->is_admin) {
            return $next($request);
        }

        abort(Response::HTTP_NOT_FOUND);
    }
}
