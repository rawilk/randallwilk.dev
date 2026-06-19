<?php

declare(strict_types=1);

it('renders on desktop', function () {
    visit(route('home'))
        ->assertNoSmoke()
        ->assertNoAccessibilityIssues()
        ->assertNoBrokenImages();
});

it('renders on mobile', function () {
    visit(route('home'))
        ->on()
        ->mobile()
        ->assertNoAccessibilityIssues()
        ->assertNoSmoke();
});

it('scrolls to the about section when the scroll down button is clicked', function () {
    visit(route('home'))
        ->assertScript('window.scrollY === 0')
        ->click(__('scroll down'))
        ->assertScript('Math.abs(window.scrollY - document.getElementById("about").offsetTop) < 8');
});
