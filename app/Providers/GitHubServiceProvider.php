<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\GitHub\GitHubApi;
use App\Support\AppConfig;
use Github\AuthMethod;
use Github\Client;
use Illuminate\Support\ServiceProvider;

class GitHubServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GitHubApi::class, function () {
            $client = new Client;

            $client->authenticate(AppConfig::gitHubToken(), null, AuthMethod::ACCESS_TOKEN);

            return new GitHubApi($client);
        });
    }
}
