<?php declare(strict_types=1);

namespace App\Domain\Transacao;

enum FormaPagamento: string
{
    case PIX = 'P';
    case CREDITO = 'C';
    case DEBITO = 'D';

    public function getTaxa(): float
    {
        return match ($this) {
            self::PIX => config('domain.formas_pagamento.pix.taxa'),
            self::CREDITO => config('domain.formas_pagamento.credito.taxa'),
            self::DEBITO => config('domain.formas_pagamento.debito.taxa'),
        };
    }
}
