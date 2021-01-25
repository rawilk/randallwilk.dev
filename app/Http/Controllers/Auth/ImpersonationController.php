<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final class ImpersonationController
{
    public function leave(): RedirectResponse
    {
        abort_unless(Session::has('impersonate'), Response::HTTP_FORBIDDEN);

        Auth::loginUsingId(Session::pull('impersonate'));

        return redirect()->route('admin.users');
    }
}
