<?php

declare(strict_types=1);

namespace Tests\Fixtures\Factories\Repositories;

use App\Enums\RepositoryType;

class UpdateRepositoryDataFactory
{
    protected string $type = RepositoryType::Package->value;

    protected ?string $scopedName = null;

    protected ?string $documentationUrl = null;

    protected ?string $blogpostUrl = null;

    protected bool $visible = true;

    protected bool $highlighted = false;

    protected bool $new = false;

    public static function new(): static
    {
        return new static;
    }

    public function create(array $extra = []): array
    {
        return $extra + [
            'type' => $this->type,
            'scoped_name' => $this->scopedName,
            'documentation_url' => $this->documentationUrl,
            'blogpost_url' => $this->blogpostUrl,
            'visible' => $this->visible,
            'highlighted' => $this->highlighted,
            'new' => $this->new,
        ];
    }
}
