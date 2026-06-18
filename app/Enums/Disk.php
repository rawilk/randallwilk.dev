<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

enum Disk: string
{
    case Avatars = 'avatars';
    case Snapshots = 'snapshots';

    public function fake(): FilesystemAdapter
    {
        return Storage::fake($this);
    }

    public function toDisk(): Filesystem
    {
        return Storage::disk($this);
    }

    public function toStorageDisk(): Filesystem
    {
        return $this->toDisk();
    }
}
