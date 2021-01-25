<?php

namespace App\Console\Commands\Github;

use App\Models\Repository;
use App\Services\Github\GithubApi;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

final class ImportGithubRepositoriesCommand extends Command
{
    private const USERNAME = 'rawilk';

    protected $signature = 'import:github-repositories {--repo= : Only import a specific (public) repository}';

    protected $description = 'Import public repositories';

    public function handle(GithubApi $api): void
    {
        $this->info('Syncing all repositories...');

        $repositories = $this->option('repo')
            ? $api->fetchRepositoryFor(self::USERNAME, $this->option('repo'))
            : $api->fetchPublicRepositories(self::USERNAME);

        $repositories->each(function (array $repositoryAttributes) use ($api) {
            $this->comment("Importing `{$repositoryAttributes['name']}`...");

            /** @var \App\Models\Repository $repository */
            $repository = Repository::withTrashed()->updateOrCreate(['name' => $repositoryAttributes['name'] ?? null], [
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
                    fn () => $api->fetchRepositoryTopics(self::USERNAME, $repository->name)
                )
            );
        });

        $this->info('Public repositories were imported!');
    }
}
