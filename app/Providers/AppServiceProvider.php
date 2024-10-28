<?php

declare(strict_types=1);

namespace App\Providers;

use App\Docs\DocumentationContentParser;
use App\Docs\DocumentationPage;
use App\Docs\DocumentationPathParser;
use App\Models;
use App\Support\Macros\MacroHelper;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ForeignKeyDefinition;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Rawilk\ProfileFilament\Models as ProfileFilamentModels;
use Rawilk\Settings\Models\Setting;

use function App\Helpers\userTimezone;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Vite::useScriptTagAttributes([
            'data-turbolinks-eval' => 'false',
            'data-turbo-eval' => 'false',
        ]);

        Date::use(CarbonImmutable::class);

        $this->bootMorphMap();

        $this->configureModels();

        $this->configureCommands();

        $this->registerMacros();
    }

    public function register(): void
    {
        $this->configureDocs();
    }

    protected function bootMorphMap(): void
    {
        Relation::enforceMorphMap([
            'authenticator_app' => ProfileFilamentModels\AuthenticatorApp::class,
            'old_user_email' => ProfileFilamentModels\OldUserEmail::class,
            'pending_user_email' => ProfileFilamentModels\PendingUserEmail::class,
            'repository' => Models\Repository::class,
            'setting' => Setting::class,
            'user' => Models\User::class,
            'webauthn_key' => Models\WebauthnKey::class,
        ]);
    }

    protected function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    protected function configureModels(): void
    {
        Model::unguard();
        Model::preventLazyLoading($this->app->isLocal());
        Model::preventAccessingMissingAttributes($this->app->isLocal());
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

    protected function registerMacros(): void
    {
        Date::macro('inUserTimezone', fn (?Authenticatable $user = null) => $this->tz(userTimezone($user)));

        if ($this->app->runningInConsole()) {
            $this->registerMigrationMacros();
        }
    }

    protected function registerMigrationMacros(): void
    {
        Blueprint::macro('user', function (string $column = 'user_id', bool $nullable = true, bool $cascade = true): ForeignKeyDefinition {
            $builder = $this->foreignId($column);

            return MacroHelper::buildForeignIdMacro(builder: $builder, nullable: $nullable, cascade: $cascade, table: 'users');
        });

        Blueprint::macro('humanKey', function () {
            return $this->string('h_key')->unique();
        });
    }
}
