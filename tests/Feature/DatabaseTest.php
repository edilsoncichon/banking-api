<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Conta\Conta;
use App\Domain\Transacao\FormaPagamento;
use App\Domain\Transacao\Transacao;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_deveria_salvar_conta_com_saldo_maximo(): void
    {
        $conta = new Conta();
        $conta->numero_conta = '123456';
        $conta->saldo = 9999999999.99;
        $conta->save();

        $contaDb = Conta::query()->firstWhere('numero_conta', '123456');

        $this->assertEquals($conta->numero_conta, $contaDb->numero_conta);
        $this->assertEquals($conta->saldo, $contaDb->saldo);
    }

    public function test_deveria_salvar_transacao_com_valor_maximo(): void
    {
        /** @var Conta $conta */
        $conta = Conta::factory()->create();

        $transacao = new Transacao();
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
}
