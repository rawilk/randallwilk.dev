<?php

declare(strict_types=1);

namespace App\Support\AppKeyRotatorActions;

use Illuminate\Support\Facades\File;
use Rawilk\AppKeyRotator\Contracts\BeforeRotatorAction;

/**
 * We need to "rollback" changes made to the encrypted .env file
 * so when we pull from source control again, it won't fail.
 */
final class BackupEnvEncryptedFileAction implements BeforeRotatorAction
{
    public function __construct(private readonly string $environment)
    {
    }

    public function handle(array $config)
    {
        if (! File::exists(base_path(".env.{$this->environment}.encrypted"))) {
            return;
        }

        File::put(
            base_path(".env.{$this->environment}.encrypted.bak"),
            File::get(base_path(".env.{$this->environment}.encrypted")),
        );
    }
}
