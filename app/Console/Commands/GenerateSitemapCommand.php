<?php

namespace App\Console\Commands;

use App\Docs\Docs;
use App\Docs\Repository;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Throwable;

final class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate our sitemaps.';

    private array $lastModifications;

    public function __construct()
    {
        parent::__construct();

        $this->lastModifications = json_decode(
            file_get_contents(base_path('last_mod.json')),
            true,
        );
    }

    public function handle(): void
    {
        $this->generateMainSitemap();
        $this->generateDocsSitemap();
        $this->generateSitemapIndex();

        $this->info('All sitemaps generated!');
    }

    private function generateMainSitemap(): void
    {
        $this->comment('Generating main sitemap...');

        SitemapGenerator::create(config('app.url'))
            ->shouldCrawl(function (UriInterface $url) {
                if (Str::startsWith($url->getPath(), '/docs')) {
                    return false;
                }

                // No need to index social login urls.
                if (Str::startsWith($url->getPath(), '/login/')) {
                    return false;
                }

                return true;
            })
            ->hasCrawled(function (Url $url) {
                /*
                 * Prevent the root url being added twice.
                 *
                 * @see https://github.com/spatie/laravel-sitemap/discussions/296#discussioncomment-185873
                 */
                if (parse_url($url->url, PHP_URL_PATH) === '/') {
                    return null;
                }

                $url->setLastModificationDate($this->getLastModificationDate($url));

                return $url;
            })
            ->writeToFile(public_path('sitemap_pages.xml'));
    }

    private function generateDocsSitemap(): void
    {
        $this->comment('Generating docs sitemap...');

        $docs = Url::create(route('docs'));
        $docs->setLastModificationDate($this->getLastModificationDate($docs));
        $sitemap = Sitemap::create()
            ->add($docs);

        $aliases = $this->getDocAliases();

        foreach ($aliases as $alias) {
            $this->addAliasPagesToSitemap($alias->pages, $sitemap);
        }

        $sitemap->writeToFile(public_path('sitemap_docs.xml'));
    }

    private function generateSitemapIndex(): void
    {
        $this->comment('Generating sitemap index...');

        SitemapIndex::create()
            ->add('/sitemap_pages.xml')
            ->add('/sitemap_docs.xml')
            ->writeToFile(public_path('sitemap.xml'));
    }

    private function addAliasPagesToSitemap(Collection $pages, Sitemap $sitemap): void
    {
        /** @var \App\Docs\DocumentationPage $page */
        foreach ($pages as $page) {
            if (Str::contains($page->slug, '_index')) {
                continue;
            }

            $lastModified = null;
            try {
                $lastModified = Date::createFromFormat(
                    'U',
                    Storage::disk("docs_{$page->repository}")->lastModified($page->getPath())
                );
            } catch (Throwable) {
                $lastModified = now();
            }

            $sitemap->add(
                Url::create($page->url)->setLastModificationDate($lastModified),
            );
        }
    }

    /**
     * @return \Illuminate\Support\Collection<int, \App\Docs\Alias>
     */
    private function getDocAliases(): Collection
    {
        return app(Docs::class)->getRepositories()
            ->map(fn (Repository $repository) => $repository->aliases)
            ->values()
            ->flatten();
    }

    /**
     * We want to try and be as accurate as we can with last mod dates.
     * To do this, we will store the last modified date in a `last_mod.json`
     * file, and parse it here.
     */
    private function getLastModificationDate(Url $url): DateTimeInterface
    {
        $path = parse_url($url->url, PHP_URL_PATH);

        if ($lastModified = ($this->lastModifications[$path] ?? null)) {
            return Date::parse($lastModified);
        }

        /*
         * If we don't have a last modified date for a specific page, we'll fall back
         * on the `_site` key.
         */
        $date = $this->lastModifications['_site'] ?? now();

        return Date::parse($date);
    }
}
