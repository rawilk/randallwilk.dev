<?php

declare(strict_types=1);

use App\Models\User;
use App\Rules\UniqueEmail;
use Illuminate\Support\Facades\Validator;

it('fails when the email already exists with different casing', function () {
    User::factory()->create([
        'email' => 'email@example.com',
    ]);

    $validator = Validator::make([
        'email' => 'EMAIL@example.com',
    ], [
        'email' => [new UniqueEmail],
    ]);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->get('email'))->not->toBeEmpty();
});

it('passes when the edited users email is unchanged with different casing', function () {
    $user = User::factory()->create([
        'email' => 'email@example.com',
    ]);

    $validator = Validator::make([
        'email' => 'EMAIL@example.com',
    ], [
        'email' => [new UniqueEmail($user)],
    ]);

    expect($validator->passes())->toBeTrue();
});

it('fails when the edited users email matches another user with different casing', function () {
    $user = User::factory()->create([
        'email' => 'one@example.com',
    ]);

    User::factory()->create([
        'email' => 'two@example.com',
    ]);

    $validator = Validator::make([
        'email' => 'TWO@example.com',
    ], [
        'email' => [new UniqueEmail($user)],
    ]);

    expect($validator->fails())->toBeTrue();
});
