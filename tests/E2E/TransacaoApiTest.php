<?php

declare(strict_types=1);

namespace Tests\E2E;

use App\Domain\Conta\Conta;
use App\Domain\Transacao\FormaPagamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransacaoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_deveria_criar_e_descontar_transacao_debito(): void
    {
        $saldoInicial = 210.99;

        /** @var Conta $conta */
        $conta = Conta::factory()->create([
            'numero_conta' => '123456',
            'saldo' => $saldoInicial,
        ]);

        $formaPagamento = FormaPagamento::DEBITO;
        $valorTransacao = 10.99;

        $response = $this->postJson('/api/transacao', [
            'forma_pagamento' => $formaPagamento,
            'numero_conta' => $conta->numero_conta,
            'valor' => $valorTransacao,
        ])->assertStatus(201);

        $valorTaxa = $valorTransacao * $formaPagamento->getTaxa();
        $valorEfetivo = $valorTransacao + $valorTaxa;
        $saldoEsperado = round($saldoInicial - $valorEfetivo, 2);

        $response->assertJson([
            'numero_conta' => $conta->numero_conta,
            'saldo' => $saldoEsperado,
        ]);
    }

    public function test_deveria_criar_e_descontar_transacao_credito(): void
    {
        $saldoInicial = 210.99;

        /** @var Conta $conta */
        $conta = Conta::factory()->create([
            'numero_conta' => '123456',
            'saldo' => $saldoInicial,
        ]);

        $formaPagamento = FormaPagamento::CREDITO;
        $valorTransacao = 10.99;

        $response = $this->postJson('/api/transacao', [
            'forma_pagamento' => $formaPagamento,
            'numero_conta' => $conta->numero_conta,
            'valor' => $valorTransacao,
        ])->assertStatus(201);

        $valorTaxa = $valorTransacao * $formaPagamento->getTaxa();
        $valorEfetivo = $valorTransacao + $valorTaxa;
        $saldoEsperado = round($saldoInicial - $valorEfetivo, 2);

        $response->assertJson([
            'numero_conta' => $conta->numero_conta,
            'saldo' => $saldoEsperado,
        ]);
    }

    public function test_deveria_criar_e_descontar_transacao_pix(): void
    {
        $saldoInicial = 210.99;

        /** @var Conta $conta */
        $conta = Conta::factory()->create([
            'numero_conta' => '123456',
            'saldo' => $saldoInicial,
        ]);

        $formaPagamento = FormaPagamento::PIX;
        $valorTransacao = 210.99;

        $response = $this->postJson('/api/transacao', [
            'forma_pagamento' => $formaPagamento,
            'numero_conta' => $conta->numero_conta,
            'valor' => $valorTransacao,
        ])->assertStatus(201);

        $valorTaxa = $valorTransacao * $formaPagamento->getTaxa();
        $valorEfetivo = $valorTransacao + $valorTaxa;
        $saldoEsperado = round($saldoInicial - $valorEfetivo, 2);

        $response->assertJson([
            'numero_conta' => $conta->numero_conta,
            'saldo' => $saldoEsperado,
        ]);
    }

    public function test_deveria_validar_transacao(): void
    {
        $response = $this->postJson('/api/transacao', [
            'forma_pagamento' => 'CRÉDITO',
            'numero_conta' => '',
            'valor' => -100,
        ])->assertStatus(422);

        $response->assertJson(['errors' => [
            'The numero conta field is required.',
            'The selected forma pagamento is invalid.',
            'The valor field must be at least 0.',
        ]]);

    }

    public function test_deveria_validar_se_conta_existe(): void
    {
        $response = $this->postJson('/api/transacao', [
            'forma_pagamento' => FormaPagamento::DEBITO,
            'numero_conta' => '999999',
            'valor' => 210.99,
        ])->assertStatus(404);

        $response->assertJson([
            'message' => 'Conta não encontrada.',
        ]);
    }

    public function test_deveria_validar_se_tem_saldo_suficiente(): void
    {
        $saldoInicial = 210.99;

        /** @var Conta $conta */
        $conta = Conta::factory()->create([
            'numero_conta' => '123456',
            'saldo' => $saldoInicial,
        ]);

        // O valor da transação acrescido da taxa é maior que o saldo da conta,
        // portanto, a transação não deveria ser realizada.

        $response = $this->postJson('/api/transacao', [
            'forma_pagamento' => FormaPagamento::DEBITO,
            'numero_conta' => $conta->numero_conta,
            'valor' => 210.99,
        ])->assertStatus(422);

        $response->assertJson([
            'message' => 'Saldo insuficiente.',
        ]);
    }
}
