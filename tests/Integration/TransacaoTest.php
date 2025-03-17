<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Domain\Conta\Conta;
use App\Domain\Transacao\FormaPagamento;
use App\Domain\Transacao\Transacao;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransacaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_deveria_salvar_transacao_com_valor_maximo(): void
    {
        /** @var Conta $conta */
        $conta = Conta::factory()->create();

        $transacao = new Transacao;
        $transacao->valor = 9999999999.99;
        $transacao->forma_pagamento = FormaPagamento::PIX;
        $transacao->conta()->associate($conta);
        $transacao->save();

        $transacaoDb = Transacao::query()->find($transacao->id);

        $this->assertInstanceOf(Collection::class, $conta->transacoes);
        $this->assertCount(1, $conta->transacoes);
        $this->assertInstanceOf(Transacao::class, $conta->transacoes->first());

        $this->assertEquals($transacao->valor, $transacaoDb->valor);
        $this->assertEquals($transacao->forma_pagamento, $transacaoDb->forma_pagamento);
        $this->assertEquals($transacao->conta->id, $transacaoDb->conta->id);
    }

    public function test_deveria_calcular_valor_da_taxa_com_pagamento_pix(): void
    {
        $transacao = new Transacao;
        $transacao->forma_pagamento = FormaPagamento::PIX;
        $transacao->valor = 100;

        $this->assertEquals(0, $transacao->calcularValorTaxa());
    }

    public function test_deveria_calcular_valor_da_taxa_com_pagamento_credito(): void
    {
        $transacao = new Transacao;
        $transacao->forma_pagamento = FormaPagamento::CREDITO;
        $transacao->valor = 100;

        $this->assertEquals(5, $transacao->calcularValorTaxa());
    }

    public function test_deveria_calcular_valor_da_taxa_com_pagamento_debito(): void
    {
        $transacao = new Transacao;
        $transacao->forma_pagamento = FormaPagamento::DEBITO;
        $transacao->valor = 100;

        $this->assertEquals(3, $transacao->calcularValorTaxa());
    }

    public function test_deveria_calcular_valor_efetivo_com_pagamento_pix(): void
    {
        $transacao = new Transacao;
        $transacao->forma_pagamento = FormaPagamento::PIX;
        $transacao->valor = 100;

        $this->assertEquals(100, $transacao->calcularValorEfetivo());
    }

    public function test_deveria_calcular_valor_efetivo_com_pagamento_credito(): void
    {
        $transacao = new Transacao;
        $transacao->forma_pagamento = FormaPagamento::CREDITO;
        $transacao->valor = 100;

        $this->assertEquals(105, $transacao->calcularValorEfetivo());
    }

    public function test_deveria_calcular_valor_efetivo_com_pagamento_debito(): void
    {
        $transacao = new Transacao;
        $transacao->forma_pagamento = FormaPagamento::DEBITO;
        $transacao->valor = 100;

        $this->assertEquals(103, $transacao->calcularValorEfetivo());
    }
}
