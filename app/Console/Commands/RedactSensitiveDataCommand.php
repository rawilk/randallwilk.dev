<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\Database\Redaction;
use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class RedactSensitiveDataCommand extends Command
{
    use Prohibitable;

    /** @var array<int, class-string<\App\Support\Database\Redaction\Contracts\Redactor>> */
    protected const array REDACTORS = [
        Redaction\UserRedactor::class,
    ];

    protected $signature = 'app:redact-sensitive-data';

    protected $description = 'Redact sensitive information in the database';

    public function handle(): int
    {
        if ($this->isProhibited()) {
            return SymfonyCommand::FAILURE;
        }

        $this->components->warn('Redacting sensitive data...');

        foreach (static::REDACTORS as $redactor) {
            $redactor = new $redactor;

            $redactor->setOutput($this->output);
            $redactor->setOutputComponents($this->components);

            $redactor->handle();
        }

        $this->components->success('Sensitive data has been redacted.');

        return SymfonyCommand::SUCCESS;
    }
}
