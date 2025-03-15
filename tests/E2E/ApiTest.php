<?php

declare(strict_types=1);

namespace Tests\E2E;

use Tests\TestCase;

class ApiTest extends TestCase
{
    public function test_deveria_garantir_que_api_funciona(): void
    {
        $response = $this->get('/api');
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Banking API']);
    }
}
