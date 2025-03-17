<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Domain\Conta\Conta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContaTest extends TestCase
{
    use RefreshDatabase;

    public function test_deveria_salvar_conta_com_saldo_maximo(): void
    {
        $conta = new Conta;
        $conta->numero_conta = '123456';
        $conta->saldo = 9999999999.99;
        $conta->save();

        $contaDb = Conta::query()->firstWhere('numero_conta', '123456');

        $this->assertEquals($conta->numero_conta, $contaDb->numero_conta);
        $this->assertEquals($conta->saldo, $contaDb->saldo);
    }
}
