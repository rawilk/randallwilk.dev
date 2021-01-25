<?php

namespace App\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** @mixin \Eloquent */
trait HasAvatar
{
    protected static function bootHasAvatar(): void
    {
        static::deleted(static function ($hasAvatar) {
            if (Storage::disk($hasAvatar->avatarDisk())->exists($hasAvatar->avatar_path)) {
                Storage::disk($hasAvatar->avatarDisk())->delete($hasAvatar->avatar_path);
            }
        });
    }

    public function updateAvatar(UploadedFile $photo): void
    {
        tap($this->avatar_path, function ($previous) use ($photo) {
            $this->forceFill([
                'avatar_path' => $photo->store('/', $this->avatarDisk()),
            ])->save();

            if ($previous) {
                Storage::disk($this->avatarDisk())->delete($previous);
            }
        });
    }

    public function deleteAvatar(): void
    {
        Storage::disk($this->avatarDisk())->delete($this->avatar_path);

        $this->forceFill([
            'avatar_path' => null,
        ])->save();
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar_path
            ? $this->getStoredAvatarUrl()
            : $this->defaultAvatarUrl();
    }

    protected function getStoredAvatarUrl(): string
    {
        return Str::startsWith(Str::lower($this->avatar_path),  'http')
            ? $this->avatar_path
            : Storage::disk($this->avatarDisk())->url($this->avatar_path);
    }

    protected function defaultAvatarUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name->full) . '&color=7F9CF5&background=EBF4FF';
    }

    public function avatarDisk(): string
    {
        return 'avatars';
    }
}
