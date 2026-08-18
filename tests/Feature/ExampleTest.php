<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_root_returns_the_institutional_landing(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Soluciones financieras para construir tu futuro');
    }
}
