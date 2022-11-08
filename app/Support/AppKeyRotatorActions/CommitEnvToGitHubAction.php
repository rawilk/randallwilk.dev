<?php

declare(strict_types=1);

namespace App\Support\AppKeyRotatorActions;

use App\Services\GitHub\GitHubApi;
use Illuminate\Support\Facades\File;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

final class CommitEnvToGitHubAction implements RotatorAction
{
    public function __construct(private readonly string $environment)
    {
    }

    public function handle(AppKeyRotator $appKeyRotator, array $config)
    {
        if (! file_exists(base_path(".env.{$this->environment}.encrypted"))) {
            return;
        }

        $api = app(GitHubApi::class);

        $api->updateFile(
            config('services.github.username'),
            config('services.github.site_repo'),
            ".env.{$this->environment}.encrypted",
            File::get(base_path(".env.{$this->environment}.encrypted")),
            'APP_KEY rotate',
        );
    }
}
