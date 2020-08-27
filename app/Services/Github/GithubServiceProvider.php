<?php

namespace App\Services\Github;

use Github\Client;
use Illuminate\Support\ServiceProvider;

class GithubServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GithubApi::class, static function () {
            $client = new Client;

            $client->authenticate(config('services.github.token'), null, Client::AUTH_ACCESS_TOKEN);

            return new GithubApi($client);
        });
    }
}
