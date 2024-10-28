<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Storage;

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
                    callback: fn () => Storage::disk($this->avatarDisk())->delete($previous),
                    report: false,
                );
            }
        });
    }

    public function deleteAvatar(): void
    {
        if (! $this->avatar_path || $this->hasExternalAvatar()) {
            return;
        }

        Storage::disk($this->avatarDisk())->delete($this->avatar_path);

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
            : Storage::disk($this->avatarDisk())->url($this->avatar_path);
    }

    protected function avatarDisk(): string
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME'])
            ? 's3'
            : 'avatars';
    }
}
