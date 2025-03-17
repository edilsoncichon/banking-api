<?php

declare(strict_types=1);

namespace App\Domain\Transacao;

enum FormaPagamento: string
{
    case PIX = 'P';
    case CREDITO = 'C';
    case DEBITO = 'D';

    public function getTaxa(): float
    {
        return match ($this) {
            self::PIX => 0.00,
            self::CREDITO => 0.05,
            self::DEBITO => 0.03,
        };
    }
}
