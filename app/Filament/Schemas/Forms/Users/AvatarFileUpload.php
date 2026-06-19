<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Users;

use App\Enums\Disk;
use Filament\Forms\Components\FileUpload;

class AvatarFileUpload extends FileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('users/resource.form.avatar.label'));

        $this->belowContent(__('filament/forms.optional_field'));

        $this->disk(Disk::Avatars->value);

        $this->avatar();

        $this->image();

        $this->imageEditor();

        $this->circleCropper();

        $this->maxSize(1024);
    }

    public static function getDefaultName(): ?string
    {
        return 'avatar_path';
    }
}
