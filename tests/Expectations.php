<?php

declare(strict_types=1);

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
