<?php

declare(strict_types=1);

use App\Actions\Users\DeleteUserAction;
use App\Enums\Disk;
use App\Models\User;

use function Pest\Laravel\assertModelMissing;

it('deletes a user', function () {
    $user = User::factory()->create();

    app(DeleteUserAction::class)($user);

    assertModelMissing($user);
});

it('deletes a users avatar when the user is deleted', function () {
    $disk = Disk::Avatars->fake();
    $disk->put('avatar.png', 'avatar');

    $user = User::factory()->create([
        'avatar_path' => 'avatar.png',
    ]);

    app(DeleteUserAction::class)($user);

    assertModelMissing($user);

    expect(Disk::Avatars->toDisk()->exists('avatar.png'))->toBeFalse();
});
