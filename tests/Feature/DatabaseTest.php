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

    public function test_se_consegue_criar_conta(): void
    {
        $conta = new Conta();
        $conta->numero_conta = '123456';
        $conta->saldo = 1000.00;
        $conta->save();

        $contaDb = Conta::query()->firstWhere('numero_conta', '123456');

        $this->assertEquals($conta->numero_conta, $contaDb->numero_conta);
        $this->assertEquals($conta->saldo, $contaDb->saldo);

    }

    public function test_se_consegue_criar_transacao(): void
    {
        /** @var Conta $conta */
        $conta = Conta::factory()->create();

        $transacao = new Transacao();
        $transacao->valor = 100.00;
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
