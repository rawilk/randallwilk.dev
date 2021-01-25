<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class UserProfileController
{
    public function show(Request $request): View
    {
        return view('front.pages.profile.show.index', [
            'user' => $request->user(),
        ]);
    }

    public function authentication(Request $request): View
    {
        return view('front.pages.profile.authentication.index', [
            'user' => $request->user(),
        ]);
    }
}
