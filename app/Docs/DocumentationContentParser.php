<?php

declare(strict_types=1);

namespace App\Docs;

use App\Support\Sheets\ImageRenderer;
use App\Support\Sheets\LinkRenderer;
use Illuminate\Support\HtmlString;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Element\Link;
use Spatie\Sheets\ContentParser;
use Spatie\YamlFrontMatter\YamlFrontMatter;

final class DocumentationContentParser implements ContentParser
{
    private CommonMarkConverter $commonMarkConverter;

    public function __construct()
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addInlineRenderer(Image::class, new ImageRenderer);
        $environment->addInlineRenderer(Link::class, new LinkRenderer);

        // No need to parse out code blocks server side, as we have Prism.js
        // handling this instead.
        $environment->addExtension(new TableExtension);
        $environment->addExtension(new HeadingPermalinkExtension);
        $environment->addExtension(new TableOfContentsExtension);
        $environment->addExtension(new AttributesExtension);

        $config = [
            'heading_permalink' => [
                'html_class' => 'anchor-link',
                'symbol' => '#',
            ],
            'table_of_contents' => [
                'max_heading_level' => 3,
            ],
        ];

        $this->commonMarkConverter = new CommonMarkConverter($config, $environment);
    }

    public function parse(string $contents): array
    {
        $document = YamlFrontMatter::parse($contents);

        $htmlContents = $this->commonMarkConverter->convertToHtml($document->body());

        return array_merge(
            $document->matter(),
            ['contents' => new HtmlString($htmlContents)]
        );
    }
}
