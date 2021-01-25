<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;

final class UsersController
{
    public function show(User $user): View
    {
        return view('admin.users.show.index', compact('user'));
    }
}
