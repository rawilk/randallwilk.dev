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
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        $this->registerMailEventListeners();
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

    protected function registerMailEventListeners(): void
    {
        Event::listen(function (MessageSending $event) {
            $headers = $event->message->getHeaders();

            if (Context::has('trace_id')) {
                $headers->addTextHeader('X-Trace-ID', Context::get('trace_id'));
            }

            // In addition to stuff like DKIM, these headers can help verify that a message actually
            // originated from the application, if ever necessary.
            $timestamp = (string) now()->unix();
            $id = Str::uuid()->toString();
            $signature = hash_hmac(
                'sha256',
                str($id)->append('|', $timestamp)->value(),
                config('randallwilk.secrets.email_hash_key'),
            );

            $headers->addTextHeader('X-Timestamp', $timestamp);
            $headers->addTextHeader('X-App-ID', $id);
            $headers->addTextHeader('X-App-Signature', $signature);
        });
    }
}
