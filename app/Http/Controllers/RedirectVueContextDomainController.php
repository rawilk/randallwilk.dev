<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class RedirectVueContextDomainController
{
    public function __invoke(string $url = '')
    {
        $url = str_replace('docs/', '', $url);

        $masterBranch = $this->getMasterBranch();

        return Redirect::to("https://randallwilk.dev/docs/vue-context/{$masterBranch}/{$url}", 301);
    }

    protected function getMasterBranch(): string
    {
        $repository = collect(config('docs.repositories'))
            ->firstWhere('name', 'vue-context');

        return $repository['branches']['master'];
    }
}
