<?php

declare(strict_types=1);

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

// Add better handling for 'toBe' assertion for Eloquent models.
expect()->intercept('toBe', Model::class, function (Model $model) {
    return expect($this->value->is($model))->toBeTrue();
});

// Add better handling for 'toBe' assertion for Dates.
expect()->intercept('toBe', CarbonInterface::class, function (CarbonInterface $date) {
    return expect($date->equalTo($this->value))->toBeTrue(
        "Expected date [{$date}] does not equal actual date [{$this->value}]",
    );
});

expect()->extend('modelsMatchExactly', function (Collection $expectedModels) {
    expect($this->value->pluck('id')->toArray())
        ->toEqualCanonicalizing($expectedModels->pluck('id')->toArray());
});

expect()->extend('toHavePasswordResetToken', function () {
    test()->assertDatabaseHas(config('auth.passwords.users.table'), [
        'email' => $this->value,
    ]);
});

expect()->extend('toBeMissingFromPasswordResets', function () {
    test()->assertDatabaseMissing(config('auth.passwords.users.table'), [
        'email' => $this->value,
    ]);
});

expect()->extend('toBePasswordFor', function (User $user) {
    return expect(Hash::check($this->value, $user->getAuthPassword()))->toBeTrue(
        "\"{$this->value}\" is not the the password for the user.",
    );
});
