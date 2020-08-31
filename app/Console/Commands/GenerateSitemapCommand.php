<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemaps.';

    public function handle(): void
    {
        $this->generateMainSitemap();
        $this->generateDocsSitemap();
        $this->generateSitemapIndex();

        $this->info('Sitemaps were generated.');
    }

    protected function generateMainSitemap(): void
    {
        $this->comment('Generating main sitemap...');

        SitemapGenerator::create(config('app.url'))
            ->shouldCrawl(fn (UriInterface $url) => ! Str::startsWith($url->getPath(), '/docs'))
            ->writeToFile(public_path('sitemap.xml'));
    }

    protected function generateDocsSitemap(): void
    {
        $this->comment('Generating docs sitemap...');

        SitemapGenerator::create(config('app.url'))
            ->shouldCrawl(fn (UriInterface $url) => Str::startsWith($url->getPath(), '/docs'))
            ->writeToFile(public_path('docs_sitemap.xml'));
    }

    protected function generateSitemapIndex(): void
    {
        $this->comment('Generating sitemap index...');

        SitemapIndex::create()
            ->add('/sitemap.xml')
            ->add('/docs_sitemap.xml')
            ->writeToFile(public_path('sitemap_index.xml'));
    }
}
