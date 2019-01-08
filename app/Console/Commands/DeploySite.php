<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DeploySite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rawilk:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs necessary actions for a site deployment.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('down');

        $this->clearCaches();
        $this->installAssets();
        $this->linkStorage();
        $this->optimize();

        $this->call('up');

        $this->info('Deployment complete');
    }

    /**
     * Clear all necessary file caches.
     */
    private function clearCaches() : void
    {
        $this->callSilent('rawilk:clear-json-file-cache', ['file' => 'posts']);
        $this->callSilent('rawilk:clear-json-file-cache', ['file' => 'projects']);
    }

    /**
     * Install npm and compile all the site assets.
     */
    private function installAssets() : void
    {
        $this->runShellCommand('npm install');
        $this->runShellCommand('npm run production');
    }

    /**
     * Create the symbolic link from storage to public.
     */
    private function linkStorage() : void
    {
        if (! file_exists(public_path('storage'))) {
            $this->callSilent('storage:link');
        }
    }

    /**
     * Run any optimizations necessary.
     */
    private function optimize() : void
    {
        $this->call('route:cache');
        $this->call('config:cache');
        $this->call('view:cache');
        $this->runShellCommand('composer install --optimize-autoloader --no-dev');
    }

    /**
     * Run the given shell command.
     *
     * @param string $command
     */
    private function runShellCommand(string $command) : void
    {
        try {
            $process = new Process($command, null, null, null, null);
            $process->run();
        } catch (\Exception $e) {}
    }
}
