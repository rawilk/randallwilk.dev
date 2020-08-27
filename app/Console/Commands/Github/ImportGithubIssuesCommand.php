<?php

namespace App\Console\Commands\Github;

use App\Models\Issue;
use App\Models\Repository;
use App\Services\Github\GithubApi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ImportGithubIssuesCommand extends Command
{
    protected $signature = 'import:github-issues';

    protected $description = 'Import issues';

    public function handle(GithubApi $api): void
    {
        $this->info('Importing issues from github.');

        Repository::get()->each(function (Repository $repository) use ($api) {
            $this->comment("Searching for issues in `{$repository->name}`...");

            $issues = $api->fetchOpenIssues('rawilk', $repository->name);

            $this->cleanupIssuesForRepository($repository, $issues);

            $issues
                ->each(function (array $issueData) use ($repository) {
                    $issue = Issue::updateOrCreate([
                        'repository_id' => $repository->id,
                        'number' => $issueData['number'],
                    ], [
                        'repository_id' => $repository->id,
                        'url' => $issueData['html_url'],
                        'title' => $issueData['title'],
                        'number' => $issueData['number'],
                        'created_at' => Carbon::createFromTimeString($issueData['created_at']),
                    ]);

                    $this->info("Imported {$repository->name}#{$issue->number}: `{$issue->title}`");

                    return $issue;
                });
        });

        $this->info('Issues were imported.');
    }

    protected function cleanupIssuesForRepository(Repository $repository, Collection $currentIssues): void
    {
        $closedIssues = Issue::query()
            ->where('repository_id', $repository->id)
            ->whereNotIn('number', $currentIssues->pluck('number'))
            ->get();

        if ($closedIssues->isEmpty()) {
            return;
        }

        $closedIssues->each->delete();

        $this->warn("Deleted {$closedIssues->count()} closed issues.");
    }
}
