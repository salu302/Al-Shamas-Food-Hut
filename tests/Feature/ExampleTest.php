<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_the_complaints_page_is_accessible(): void
    {
        $response = $this->get('/complaints');

        $response->assertStatus(200);
        $response->assertSee('Complaints');
    }
}
