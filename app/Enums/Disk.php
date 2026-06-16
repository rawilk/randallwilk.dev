<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

enum Disk: string
{
    case Avatars = 'avatars';
    case Snapshots = 'snapshots';

    public function toStorageDisk(): Filesystem
    {
        return Storage::disk($this->value);
    }
}
