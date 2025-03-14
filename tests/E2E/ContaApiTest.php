<?php declare(strict_types=1);

namespace Tests\E2E;

use App\Domain\Conta\Conta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_valida_conta_inexistente(): void
    {
        $response = $this->getJson('/api/conta?numero_conta=123');
        $response->assertStatus(404);
        $response->assertJson([]);
    }

    public function test_se_consulta_conta(): void
    {
        /** @var Conta $conta */
        $conta = Conta::factory()->create(['numero_conta' => 123456789, 'saldo' => 1000.99]);

        $response = $this->getJson('/api/conta?numero_conta=123456789');
        $response->assertStatus(200);

        $response->assertJson([
            'numero_conta' => $conta->numero_conta,
            'saldo' => $conta->saldo,
        ]);

        $this->assertIsInt($response->json('numero_conta'));
        $this->assertIsFloat($response->json('saldo'));
    }

    public function test_se_cria_conta(): void
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

    public function test_se_valida_duplicidade(): void
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
