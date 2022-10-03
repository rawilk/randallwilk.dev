<?php

declare(strict_types=1);

namespace App\Services\GitHub;

use Github\Client as GitHubClient;
use Github\ResultPager;
use Illuminate\Support\Collection;

final class GitHubApi
{
    public function __construct(private readonly GitHubClient $client)
    {
    }

    public function fetchPublicRepositories(string $username): Collection
    {
        /** @var \Github\Api\User $api */
        $api = $this->client->api('user');

        $paginator = new ResultPager($this->client);

        $repositories = $paginator->fetchAll($api, 'repositories', [$username]);

        return collect($repositories)->filter(fn (array $repo) => $repo['private'] === false);
    }

    public function fetchSingleRepository(string $username, string $repository): ?array
    {
        /** @var \Github\Api\Repo $api */
        $api = $this->client->api('repo');

        return rescue(fn () => $api->show($username, $repository));
    }

    public function fetchRepositoryTopics(string $username, string $repository): Collection
    {
        /** @var \Github\Api\Repo $api */
        $api = $this->client->api('repository');

        return collect($api->topics($username, $repository)['names'] ?? []);
    }
}
