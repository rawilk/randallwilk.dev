<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;

class AddLogContext
{
    public function handle(Request $request, Closure $next)
    {
        Context::add(
            'trace_id',
            $request->header('X-TRACE-ID') ?? Str::uuid()->toString(),
        );

        return $next($request);
    }
}
