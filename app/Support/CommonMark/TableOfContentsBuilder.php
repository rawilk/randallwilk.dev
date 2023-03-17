<?php

declare(strict_types=1);

namespace App\Support\CommonMark;

use DOMDocument;
use DOMElement;
use Generator;
use Illuminate\Support\Str;

/**
 * This class is to help us extract any table of contents generated
 * by the commonmark package into an array so we can render a
 * "table of contents" wherever we need on the page.
 */
final class TableOfContentsBuilder
{
    public function __construct(private readonly string $content)
    {
    }

    public static function generate(string $content): array
    {
        return (new self($content))->build();
    }

    public function build(): array
    {
        $dom = new DOMDocument;
        $dom->loadHTML(
            mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8'),
        );

        return $this->getHeadingLinks($dom);
    }

    private function getHeadingLinks(DOMDocument $document): array
    {
        if (! $tableOfContents = $this->getTableOfContents($document)) {
            return [];
        }

        $headings = [];

        foreach ($this->htmlChildren($tableOfContents) as $node) {
            if ($heading = $this->parseLi($node)) {
                $headings[] = $heading;
            }
        }

        return $headings;
    }

    private function parseLi(DOMElement $node, int $level = 0): ?array
    {
        if ($node->tagName !== 'li') {
            return null;
        }

        $data = [
            'id' => '',
            'text' => '',
            'children' => [],
        ];

        foreach ($this->htmlChildren($node) as $child) {
            if ($child->tagName === 'a') {
                $data['id'] = Str::after($child->getAttribute('href'), '#');
                $data['text'] = $child->textContent;

                continue;
            }

            // We are only going to parse 1 level deep...
            if ($child->tagName === 'ul' && $level === 0) {
                foreach ($this->htmlChildren($child) as $grandChild) {
                    if ($subHeading = $this->parseLi($grandChild, $level + 1)) {
                        $data['children'][] = $subHeading;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @return \Generator<\DOMElement>
     */
    private function htmlChildren(DOMElement $node): Generator
    {
        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeName === '#text') {
                continue;
            }

            yield $childNode;
        }
    }

    private function getTableOfContents(DOMDocument $document): ?DOMElement
    {
        foreach ($document->getElementsByTagName('ul') as $node) {
            if ($this->isTableOfContents($node)) {
                return $node;
            }
        }

        return null;
    }

    private function isTableOfContents(DOMElement $node): bool
    {
        if (! $node->hasAttribute('class')) {
            return false;
        }

        return $node->getAttribute('class') === 'table-of-contents';
    }
}
