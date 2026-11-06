<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegistrationCrudTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_homepage_redirects_to_the_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    }
}
