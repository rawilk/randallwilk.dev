<?php

declare(strict_types=1);

use App\Actions\Users\CreateUserAction;
use App\Notifications\Users\WelcomeNotification;

beforeEach(function () {
    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('creates a new user', function () {
    Notification::fake();

    $user = app(CreateUserAction::class)([
        'name' => 'Dexter Morgan',
        'email' => 'dexter@example.test',
        'is_admin' => false,
        'timezone' => 'America/Chicago',
    ]);

    expect($user->refresh())->password->not->toBeNull();

    Notification::assertSentTo($user, WelcomeNotification::class);
});
