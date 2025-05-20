<?php

declare(strict_types=1);

namespace App\Providers;

use App\Console\Commands\RedactSensitiveDataCommand;
use App\Console\Commands\RefreshStagingDataCommand;
use App\Docs\DocumentationContentParser;
use App\Docs\DocumentationPage;
use App\Docs\DocumentationPathParser;
use App\Support\Macros\MigrationMacroHelper;
use App\Support\MorphMapConfig;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

use function App\Helpers\userTimezone;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureDates();
        $this->configureMacros();
        $this->configureModels();
        $this->configureUrl();
        $this->configureVite();
        $this->configureMorphMap();
    }

    public function register(): void
    {
        $this->configureDocs();
    }

    protected function configureMorphMap(): void
    {
        MorphMapConfig::configure();
    }

    protected function configureCommands(): void
    {
        $isProduction = $this->app->isProduction();

        DB::prohibitDestructiveCommands($isProduction);
        RedactSensitiveDataCommand::prohibit($isProduction);
        RefreshStagingDataCommand::prohibit(! $isProduction);
    }

    protected function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    protected function configureModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict(! $this->app->isProduction());
    }

    protected function configureDocs(): void
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

    protected function configureMacros(): void
    {
        if ($this->app->runningInConsole()) {
            MigrationMacroHelper::register();
        }

        Date::macro('inUserTimezone', fn (?Authenticatable $user = null) => $this->tz(userTimezone($user)));
    }

    protected function configureUrl(): void
    {
        URL::forceHttps();
    }

    protected function configureVite(): void
    {
        Vite::usePrefetchStrategy('aggressive');
    }
}
