<?php

namespace App\Http\Controllers;

class RedirectDocAssetsController
{
    public function __invoke()
    {
        $newPath = str_replace('docs/', 'doc_files/', request()->path());

        return redirect()->to("/{$newPath}");
    }
}
