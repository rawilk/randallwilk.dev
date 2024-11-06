<?php

declare(strict_types=1);

namespace App\Support\Database;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\DbDumper\Compressors\GzipCompressor;
use Spatie\DbDumper\Databases\PostgreSql;

readonly class DbDumper
{
    public function __construct(
        protected string $connection = 'pgsql',
        protected bool $compress = true,
    ) {
    }

    public static function make(string $connection = 'pgsql', bool $compress = true): static
    {
        return new static($connection, $compress);
    }

    public function resolve(): PostgreSql
    {
        $dbConfig = $this->config();

        $host = Arr::get($dbConfig, 'read.host', Arr::get($dbConfig, 'host'));

        $dumper = PostgreSql::create()
            ->setHost($host)
            ->setUserName($dbConfig['username'])
            ->setPassword($dbConfig['password'])
            ->setDbName($dbConfig['database'])
            ->setDumpBinaryPath($dbConfig['dump_command_path'] ?? '')
            ->setTimeout($dbConfig['dump_command_timeout'] ?? 0);

        if ($this->compress) {
            $dumper->useCompressor(new GzipCompressor);
        }

        if (isset($dbConfig['port'])) {
            $dumper->setPort((int) $dbConfig['port']);
        }

        return $dumper;
    }

    public function config(): array
    {
        return once(fn () => DB::connection($this->connection)->getConfig());
    }
}
