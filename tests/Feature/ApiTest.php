<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    public function test_the_api_returns_a_successful_response(): void
    {
        $response = $this->get('/api');
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Banking API',]);
    }
}
