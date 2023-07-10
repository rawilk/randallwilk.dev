<?php

declare(strict_types=1);

namespace App\Support\AppKeyRotatorActions;

use Illuminate\Support\Facades\File;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

final readonly class RestoreEncryptedEnvAction implements RotatorAction
{
    public function __construct(private readonly string $environment)
    {
    }

    public function handle(AppKeyRotator $appKeyRotator, array $config): void
    {
        if (! File::exists(base_path(".env.{$this->environment}.encrypted.bak"))) {
            return;
        }

        File::put(
            base_path(".env.{$this->environment}.encrypted"),
            File::get(base_path(".env.{$this->environment}.encrypted.bak")),
        );

        File::delete(base_path(".env.{$this->environment}.encrypted.bak"));
    }
}
