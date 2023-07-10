<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Docs\Docs;
use Illuminate\Contracts\View\View;

final class SiteMapController
{
    public function __invoke(Docs $docs): View
    {
        return view('front.pages.sitemap.index', [
            'repositories' => $docs->getRepositories(),
        ]);
    }
}
