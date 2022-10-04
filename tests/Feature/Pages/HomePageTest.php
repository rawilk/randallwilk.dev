<?php

declare(strict_types=1);

use App\Enums\SkillsEnum;

it('can render the homepage', function () {
    $this->get(route('home'))->assertSuccessful();
});

it('shows my skills', function () {
    $response = $this->get(route('home'));

    foreach (SkillsEnum::cases() as $category) {
        $response->assertSeeText($category->label());

        foreach (config("site.skills.{$category->value}") as $skill) {
            $response->assertSeeText($skill);
        }
    }
});
