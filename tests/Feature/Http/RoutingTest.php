<?php

declare(strict_types=1);

use App\Enums\SkillType;

use function Pest\Laravel\get;

/**
 * Note: I was originally going to use the spatie/pest-plugin-route-testing
 * package to test the routes, however there is an issue right now with
 * tests that have some kind of setUp method defined.
 *
 * So for now, since there aren't that many routes in the application,
 * I'll just test them all manually.
 *
 * @see https://github.com/spatie/pest-plugin-route-testing/issues/6
 */
test('front-end routes', function (string $route) {
    get(route($route))->assertSuccessful();
})->with([
    'home',
    'contact',
    'docs',
    'legal.disclaimer',
    'legal.index',
    'legal.privacy',
    'legal.terms',
    'open-source.packages',
    'open-source.projects',
    'open-source.support',
    'sitemap',
    'uses',
]);

test('redirects', function (string $path) {
    get($path)->assertRedirect();
})->with([
    'projects',
]);

test('home page test', function () {
    $response = get(route('home'));

    foreach (SkillType::cases() as $case) {
        $response->assertSeeText($case->getLabel());

        foreach (config("randallwilk.skills.{$case->value}") as $skill) {
            $response->assertSeeText($skill);
        }
    }
});
