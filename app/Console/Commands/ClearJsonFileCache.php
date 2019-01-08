<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearJsonFileCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rawilk:clear-json-file-cache
                            {file : The file cache to clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the given json file cache.';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $path = resource_path("json/{$this->argument('file')}.json");

        cache()->forget($path);

        // re-cache the file
        getDataFromJsonFile("{$this->argument('file')}.json");

        $this->info("Cache cleared for: {$path}");
    }
}
