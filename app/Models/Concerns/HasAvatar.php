<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Disk;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Throwable;

/**
 * @property null|string $avatar_path
 * @property-read string $avatar_url
 *
 * @mixin \Eloquent
 */
trait HasAvatar
{
    public function updateAvatar(?string $avatar): void
    {
        tap($this->avatar_path, function (?string $previous) use ($avatar): void {
            $this->fill(['avatar_path' => $avatar])->save();

            if ($previous && ! $this->hasExternalAvatar($previous)) {
                rescue(
                    callback: fn () => Disk::Avatars->toStorageDisk()->delete($previous),
                    report: false,
                );
            }
        });
    }

    public function deleteAvatar(): void
    {
        if (blank($this->avatar_path)) {
            return;
        }

        if (! $this->hasExternalAvatar()) {
            try {
                Disk::Avatars->toStorageDisk()->delete($this->avatar_path);
            } catch (Throwable) {
            }
        }

        $this->fill(['avatar_path' => null])->save();
    }

    public function hasExternalAvatar(?string $path = null): bool
    {
        $path ??= $this->avatar_path;

        return Str::startsWith(Str::lower($path), 'http');
    }

    public function hasAvatar(): bool
    {
        return filled($this->avatar_path);
    }

    public function defaultAvatarUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name->initials) . '&color=7F9CF5&background=EBF4FF';
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->hasAvatar()
                ? $this->getStoredAvatarUrl()
                : $this->defaultAvatarUrl(),
        )->shouldCache();
    }

    protected function getStoredAvatarUrl(): string
    {
        return $this->hasExternalAvatar()
            ? $this->avatar_path
            : Disk::Avatars->toStorageDisk()->url($this->avatar_path);
    }
}
