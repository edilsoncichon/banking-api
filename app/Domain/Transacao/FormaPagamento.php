<?php

declare(strict_types=1);

namespace App\Domain\Transacao;

enum FormaPagamento: string
{
    case PIX = 'P';
    case CREDITO = 'C';
    case DEBITO = 'D';
}
