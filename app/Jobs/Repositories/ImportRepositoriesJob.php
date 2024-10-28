<?php

declare(strict_types=1);

namespace App\Jobs\Repositories;

use App\Enums\ProgrammingLanguage;
use App\Models\Repository;
use App\Services\GitHub\GitHubApi;
use DateTimeInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class ImportRepositoriesJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected GitHubApi $api;

    public function __construct(protected readonly string $username, protected readonly ?string $repositoryName = null)
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
            $language = ProgrammingLanguage::tryFrom($repositoryAttributes['language'] ?? '') ?? ProgrammingLanguage::Unknown;

            $repository = Repository::withTrashed()->updateOrCreate(['name' => $repositoryAttributes['name'] ?? null], [
                'name' => $repositoryAttributes['name'],
                'description' => $repositoryAttributes['description'],
                'stars' => $repositoryAttributes['stargazers_count'],
                'language' => $language,
                'repository_created_at' => Date::createFromFormat(DateTimeInterface::ATOM, $repositoryAttributes['created_at']),
            ]);

            $repository->setTopics(
                cache()->remember(
                    key: "remember_topics-{$repository->name}",
                    ttl: now()->addHour(),
                    callback: fn () => $this->api->fetchRepositoryTopics($this->username, $repository->name),
                )
            );
        });
    }

    protected function fetchRepositories(): Collection
    {
        if ($this->repositoryName) {
            return collect([$this->api->fetchSingleRepository($this->username, $this->repositoryName)])->filter();
        }

        return $this->api->fetchPublicRepositories($this->username);
    }
}
