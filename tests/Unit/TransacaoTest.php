<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\Transacao\FormaPagamento;
use App\Domain\Transacao\Transacao;
use PHPUnit\Framework\TestCase;

class TransacaoTest extends TestCase
{
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
