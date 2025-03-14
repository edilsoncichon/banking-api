<?php declare(strict_types=1);

namespace Tests\E2E;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_cria_conta_via_api(): void
    {
        $data = [
            'numero_conta' => 123,
            'saldo' => 1000,
        ];
        $response = $this->postJson('/api/conta', $data);

        $response->assertStatus(201);

        $response->assertJson([
            'numero_conta' => $data['numero_conta'],
            'saldo' => $data['saldo'],
        ]);
    }

    public function test_se_valida_numero_conta(): void
    {
        $data = [
            'numero_conta' => 'abc',
            'saldo' => 1000,
        ];
        $response = $this->postJson('/api/conta', $data);

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                'The numero conta field must be an integer.',
            ],
        ]);
    }

    public function test_se_valida_saldo_menor_que_zero(): void
    {
        $data = [
            'numero_conta' => 123,
            'saldo' => -100,
        ];
        $response = $this->postJson('/api/conta', $data);

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                'The saldo field must be at least 0.',
            ],
        ]);
    }

    public function test_se_valida_conta_existente(): void
    {
        $data = [
            'numero_conta' => 123,
            'saldo' => 1000,
        ];
        $this->postJson('/api/conta', $data);

        $response = $this->postJson('/api/conta', $data);

        $response->assertStatus(400);

        $response->assertJson([
            'message' => 'Conta já existe.',
        ]);
    }

    public function test_se_dados_conta_sao_obrigatorios(): void
    {
        $response = $this->postJson('/api/conta', []);

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                'The numero conta field is required.',
                'The saldo field is required.',
            ],
        ]);
    }
}
