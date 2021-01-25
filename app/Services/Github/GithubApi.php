<?php

namespace App\Services\Github;

use Github\Client;
use Github\ResultPager;
use Illuminate\Support\Collection;

class GithubApi
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchPublicRepositories(string $username): Collection
    {
        $api = $this->client->api('user');

        $paginator = new ResultPager($this->client);

        $repositories = $paginator->fetchAll($api, 'repositories', [$username]);

        return collect($repositories)
            ->filter(fn ($repo) => $repo['private'] === false);
    }

    // Fetch a single repository
    public function fetchRepositoryFor(string $username, string $repository): Collection
    {
        return $this->fetchPublicRepositories($username)
            ->filter(fn ($repo) => $repo['name'] === $repository)
            ->values();
    }

    public function fetchOpenIssues(string $username, string $repository, array $labelFilters = []): Collection
    {
        $api = $this->client->api('issue');

        $paginator = new ResultPager($this->client);

        $issues = $paginator->fetchAll($api, 'all', [$username, $repository, ['state' => 'open']]);

        return collect($issues)->filter(static function (array $issue) use ($labelFilters) {
            if (! empty($issue['pull_request'])) {
                return false; // we don't want pull requests to be factored into this number.
            }

            if (! $labelFilters) {
                return true;
            }

            return collect($issue['labels'] ?? [])->filter(static function (array $label) use ($labelFilters) {
                return in_array($label['name'] ?? null, $labelFilters, true);
            })->isNotEmpty();
        });
    }

    public function fetchRepositoryTopics(string $username, string $repository): Collection
    {
        $api = $this->client->api('repository');

        return collect($api->topics($username, $repository)['names'] ?? []);
    }
}
