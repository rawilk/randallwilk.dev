<?php

declare(strict_types=1);

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Contracts\Process\ProcessResult;

class DatabaseImportFailed extends Exception
{
    public static function processFailed(ProcessResult $result): static
    {
        return new static(
            'The import process failed with a non successful exit code.' . static::formatProcessOutput($result),
        );
    }

    protected static function formatProcessOutput(ProcessResult $result): string
    {
        $output = $result->output() ?: '<no output>';
        $errorOutput = $result->errorOutput() ?: '<no output>';
        $exitCodeText = $result->exitCode() ?: '<no exit text>';

        return <<<CONSOLE

            Command:
            ========
            {$result->command()}

            Exit code:
            ==========
            {$exitCodeText}

            Output:
            =======
            {$output}

            Error output:
            =============
            {$errorOutput}
        CONSOLE;
    }
}
