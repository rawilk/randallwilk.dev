<?php

declare(strict_types=1);

namespace App\Support\Database;

use App\Exceptions\Database\DatabaseImportFailed;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Spatie\DbDumper\Databases\PostgreSql;

class PostgreDumpImporter
{
    protected PostgreSql $postgreSql;

    public function __construct(protected DbDumper $dumper)
    {
        $this->postgreSql = $dumper->resolve();
    }

    public static function fromDumper(DbDumper $dumper): static
    {
        return new static($dumper);
    }

    public function import(string $file): void
    {
        $process = $this->getProcess($file);

        $result = $process->run();

        $this->checkIfImportWasSuccessful($result);
    }

    protected function getProcess(string $file): PendingProcess
    {
        $command = $this->getImportCommand($file);

        fwrite($this->tmpFileHandle(), $this->postgreSql->getContentsOfCredentialsFile());
        $temporaryCredentialsFile = stream_get_meta_data($this->tmpFileHandle())['uri'];

        $envVars = (fn () => $this->getEnvironmentVariablesForDumpCommand($temporaryCredentialsFile))->call($this->postgreSql);

        $timeout = (fn () => $this->timeout)->call($this->postgreSql);

        return Process::timeout($timeout)
            ->env($envVars)
            ->command($command);
    }

    protected function getImportCommand(string $file): string
    {
        $quote = (fn () => $this->determineQuote())->call($this->postgreSql);
        $config = $this->dumper->config();

        $command = [
            "{$quote}psql{$quote}",
            '-U "' . data_get($config, 'username') . '"',
            "-h {$this->postgreSql->getHost()}",
            '-p ' . data_get($config, 'port'),
            $this->postgreSql->getDbName(),
        ];

        return $this->echoToFile(implode(' ', $command), $file);
    }

    protected function echoToFile(string $command, string $dumpFile): string
    {
        $dumpFile = '"' . addcslashes($dumpFile, '\\"') . '"';

        return $command . ' < ' . $dumpFile;
    }

    protected function tmpFileHandle(): mixed
    {
        return once(fn () => tmpfile());
    }

    protected function checkIfImportWasSuccessful(ProcessResult $result): void
    {
        throw_unless(
            $result->successful(),
            DatabaseImportFailed::processFailed($result),
        );
    }
}
