<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\GitHub\Repository;
use Illuminate\Contracts\View\View;

final class ShowRepositoryController
{
    public function __invoke(Repository $repository): View
    {
        return view('admin.repositories.show.index', compact('repository'));
    }
}
