<?php

declare(strict_types=1);

namespace App\Providers;

use App\Docs\DocumentationContentParser;
use App\Docs\DocumentationPage;
use App\Docs\DocumentationPathParser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Model::unguard();

        $this->configureDocs();
    }

    private function configureDocs(): void
    {
        foreach (config('docs.repositories') as $docsRepository) {
            config()->set("filesystems.disks.docs_{$docsRepository['name']}", [
                'driver' => 'local',
                'root' => storage_path("docs/{$docsRepository['name']}"),
            ]);

            config()->set("sheets.collections.{$docsRepository['name']}", [
                'disk' => "docs_{$docsRepository['name']}",
                'sheet_class' => DocumentationPage::class,
                'path_parser' => DocumentationPathParser::class,
                'content_parser' => DocumentationContentParser::class,
            ]);
        }
    }
}
