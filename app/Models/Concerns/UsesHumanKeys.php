<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Rawilk\HumanKeys\Concerns\HasHumanKey;

trait UsesHumanKeys
{
    use HasHumanKey;

    public function humanKeys(): array
    {
        return ['h_key'];
    }

    public function getRouteKeyName(): string
    {
        return 'h_key';
    }
}
