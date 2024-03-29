<?php

declare(strict_types=1);

namespace App\Jobs\Repositories;

use App\Enums\ProgrammingLanguageEnum;
use App\Models\GitHub\Repository;
use App\Services\GitHub\GitHubApi;
use DateTimeInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

final class ImportRepositoriesJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private GitHubApi $api;

    public function __construct(private readonly string $username, private readonly ?string $repositoryName = null)
    {
    }

    public function handle(): void
    {
        if ($this->batch()?->canceled()) {
            return;
        }

        $this->api = app(GitHubApi::class);

        $repositories = $this->fetchRepositories();

        $repositories->each(function (array $repositoryAttributes) {
            $language = ProgrammingLanguageEnum::tryFrom($repositoryAttributes['language'] ?? '') ?? ProgrammingLanguageEnum::Unknown;

            $repository = Repository::withTrashed()->updateOrCreate(['name' => $repositoryAttributes['name'] ?? null], [
                'name' => $repositoryAttributes['name'],
                'description' => $repositoryAttributes['description'],
                'stars' => $repositoryAttributes['stargazers_count'],
                'language' => $language,
                'repository_created_at' => Date::createFromFormat(DateTimeInterface::ATOM, $repositoryAttributes['created_at']),
            ]);

            $repository->setTopics(
                Cache::remember(
                    "remember_topics-{$repository->name}",
                    3600,
                    fn () => $this->api->fetchRepositoryTopics($this->username, $repository->name),
                )
            );
        });
    }

    private function fetchRepositories(): Collection
    {
        if ($this->repositoryName) {
            return collect([$this->api->fetchSingleRepository($this->username, $this->repositoryName)])->filter();
        }

        return $this->api->fetchPublicRepositories($this->username);
    }
}
