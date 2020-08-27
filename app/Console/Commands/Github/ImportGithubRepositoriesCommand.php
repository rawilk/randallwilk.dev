<?php

namespace App\Console\Commands\Github;

use App\Models\Repository;
use App\Services\Github\GithubApi;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ImportGithubRepositoriesCommand extends Command
{
    protected $signature = 'import:github-repositories';

    protected $description = 'Import public repositories';

    public function handle(GithubApi $api): void
    {
        $this->info('Syncing all repositories...');

        $repositories = $api->fetchPublicRepositories('rawilk');

        $repositories->each(function (array $repositoryAttributes) use ($api) {
            $this->comment("Importing `{$repositoryAttributes['name']}`...");

            /** @var \App\Models\Repository $repository */
            $repository = Repository::updateOrCreate(['name' => $repositoryAttributes['name'] ?? null], [
                'name' => $repositoryAttributes['name'],
                'description' => $repositoryAttributes['description'],
                'stars' => $repositoryAttributes['stargazers_count'],
                'language' => $repositoryAttributes['language'],
                'repository_created_at' => Carbon::createFromFormat(DateTime::ATOM, $repositoryAttributes['created_at']),
            ]);

            $repository->setTopics(
                Cache::remember(
                    "repository_topics-{$repository->name}",
                    3600,
                    fn () => $api->fetchRepositoryTopics('rawilk', $repository->name)
                )
            );
        });

        $this->info('Public repositories were imported!');
    }
}
