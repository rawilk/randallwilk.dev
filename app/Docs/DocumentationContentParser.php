<?php

declare(strict_types=1);

namespace App\Docs;

use App\Support\CommonMark\ImageRenderer;
use App\Support\CommonMark\LinkRenderer;
use Illuminate\Support\HtmlString;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use Spatie\Sheets\ContentParser;
use Spatie\YamlFrontMatter\YamlFrontMatter;

final class DocumentationContentParser implements ContentParser
{
    private MarkdownRenderer $markdownRenderer;

    public function __construct()
    {
        /*
         * We will disable parsing code blocks server side, as we will use
         * Prism.js to handle this instead.
         */

        $this->markdownRenderer = app(MarkdownRenderer::class)
            ->highlightCode(false)
            ->addInlineRenderer(Image::class, new ImageRenderer)
            ->addInlineRenderer(Link::class, new LinkRenderer)
            ->addExtension(new TableExtension)
            ->addExtension(new HeadingPermalinkExtension)
            ->addExtension(new TableOfContentsExtension)
            ->addExtension(new AttributesExtension)
            ->commonmarkOptions([
                'heading_permalink' => [
                    'html_class' => 'anchor-link',
                    'symbol' => '#',
                    'id_prefix' => 'user-content',
                    'fragment_prefix' => 'user-content',
                ],
                'table_of_contents' => [
                    'max_heading_level' => 3,
                ],
            ]);
    }

    public function parse(string $contents): array
    {
        $document = YamlFrontMatter::parse($contents);

        $htmlContents = $this->markdownRenderer->toHtml($document->body());

        return array_merge(
            $document->matter(),
            ['contents' => new HtmlString($htmlContents)],
        );
    }
}
