<?php

declare(strict_types=1);

it('renders with no accessibility issues', function () {
    visit(route('contact'))
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues();
});
