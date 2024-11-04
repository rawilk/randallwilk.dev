<?php

declare(strict_types=1);

use App\Actions\Users\DeleteUserAction;
use App\Models\User;

it('deletes a user', function () {
    $user = User::factory()->create();

    app(DeleteUserAction::class)($user);

    $this->assertModelMissing($user);
});
