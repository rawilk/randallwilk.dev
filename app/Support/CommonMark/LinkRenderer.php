<?php

declare(strict_types=1);

namespace App\Support\CommonMark;

use Illuminate\Support\Str;
use InvalidArgumentException;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\RegexHelper;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

final class LinkRenderer implements NodeRendererInterface, ConfigurationAwareInterface
{
    private ConfigurationInterface $config;

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        if (! ($node instanceof Link)) {
            throw new InvalidArgumentException('Incompatible inline type: ' . get_class($node));
        }

        $attrs = $node->data->get('attributes', []);

        $forbidUnsafeLinks = ! $this->config->get('allow_unsafe_links');
        if (! ($forbidUnsafeLinks && RegexHelper::isLinkPotentiallyUnsafe($node->getUrl()))) {
            $attrs['href'] = $node->getUrl();
        }

        if (Str::startsWith($attrs['href'], '/')) {
            $attrs['href'] = Str::start($attrs['href'], '/docs');
        }

        if (isset($node->data['title'])) {
            $attrs['title'] = $node->data['title'];
        }

        if (isset($attrs['target']) && $attrs['target'] === '_blank' && ! isset($attrs['rel'])) {
            $attrs['rel'] = 'noopener noreferrer';
        }

        return new HtmlElement('a', $attrs, $childRenderer->renderNodes($node->children()));
    }
}
