<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class RedirectDocsDomainController
{
    public function __invoke(string $url = '')
    {
        return Redirect::to("https://randallwilk.dev/docs/{$url}", 301);
    }
}
