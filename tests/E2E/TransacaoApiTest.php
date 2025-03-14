<?php declare(strict_types=1);

namespace Tests\E2E;

use App\Domain\Conta\Conta;
use App\Domain\Transacao\FormaPagamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransacaoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_cria_transacao(): void
    {
        /** @var Conta $conta */
        $conta = Conta::factory()->create([
            'numero_conta' => '123456',
            'saldo' => 210.99
        ]);

        $response = $this->postJson('/api/transacao', [
            'forma_pagamento' => FormaPagamento::DEBITO,
            'numero_conta' => $conta->numero_conta,
            'valor' => 10.99
        ]);

        $response->assertStatus(201);

        $saldoEsperado = round(210.99 - (10.99 + (10.99 * 0.03)), 2);
        $response->assertJson([
            'numero_conta' => $conta->numero_conta,
            'saldo' => $saldoEsperado,
        ]);
    }

    //TODO calcular a taxa e descontar o valor da conta...

    //TODO Validar transacao com parametros invalidos

    //TODO Validar caso nao tenha saldo disponivel
}
