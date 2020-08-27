<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /** @test */
    public function can_view_the_home_page(): void
    {
        $this->get(route('home'))
            ->assertSuccessful();
    }
}
