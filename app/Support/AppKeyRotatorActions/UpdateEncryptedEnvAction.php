<?php

declare(strict_types=1);

namespace App\Support\AppKeyRotatorActions;

use Illuminate\Foundation\Console\EnvironmentEncryptCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

final class UpdateEncryptedEnvAction implements RotatorAction
{
    public function __construct(
        private readonly string $key,
        private readonly string $environment,
        private readonly bool $updateEnvironmentSpecificFile,
    ) {
    }

    public function handle(AppKeyRotator $appKeyRotator, array $config)
    {
        if ($this->updateEnvironmentSpecificFile) {
            File::put(
                base_path(".env.{$this->environment}"),
                File::get(base_path('.env')),
            );
        }

        Artisan::call(EnvironmentEncryptCommand::class, [
            '--force' => true,
            '--env' => $this->environment,
            '--key' => $this->key,
        ]);
    }
}
