<?php

declare(strict_types=1);

use Illuminate\Auth\Events\Logout;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->panel = filament()->getPanel('admin');
});

it('logs a user out', function () {
    $user = adminUser();

    Event::fake();

    actingAs($user)
        ->post($this->panel->getLogoutUrl())
        ->assertRedirect($this->panel->getLoginUrl());

    $this->assertGuest();

    Event::assertDispatched(Logout::class, function (Logout $event) use ($user) {
        expect($event->user)->toBe($user);

        return true;
    });
});

it('does not try to log guests out', function () {
    Event::fake();

    post($this->panel->getLogoutUrl())
        ->assertRedirect($this->panel->getLoginUrl());

    $this->assertGuest();

    Event::assertNotDispatched(Logout::class);
});
