<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Repository;
use Illuminate\Contracts\View\View;

final class RepositoriesController
{
    public function show(Repository $repository): View
    {
        return view('admin.repositories.show.index', compact('repository'));
    }
}
