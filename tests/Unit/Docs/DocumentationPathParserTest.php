<?php

declare(strict_types=1);

use App\Docs\DocumentationPathParser;

it('parses a doc page path', function (string $path, string $expectedSlug, ?string $expectedAlias = null) {
    $parser = new DocumentationPathParser;

    $result = $parser->parse($path);

    expect($result)->toEqualCanonicalizing([
        'slug' => $expectedSlug,
        'alias' => $expectedAlias,
    ]);
})->with([
    ['installation', 'installation'],
    ['installation.md', 'installation'],
    ['api/settings', 'api', 'settings'],
    ['advanced-usage/cache.md', 'advanced-usage', 'cache'],
]);
