<?php

declare(strict_types=1);

namespace App\Services\GitHub;

use Exception;
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

    public function updateFile(string $username, string $repository, string $path, ?string $content, string $message): array
    {
        /** @var \Github\Api\Repository\Contents $api */
        $api = $this->client->api('repo')->contents();

        // We need the sha to update a file.
        $sha = rescue(function () use ($api, $username, $repository, $path) {
            $file = $api->show($username, $repository, $path);

            return $file['sha'];
        });

        throw_unless($sha, Exception::class, 'File not found in repository.');

        return $api->update($username, $repository, $path, $content, $message, $sha);
    }
}
