<?php

declare(strict_types=1);

namespace App\Domain\Conta\Repository;

use App\Domain\Conta\Conta;

final class FindByAccountNumberRepository
{
    public function handle(int $numeroConta): ?Conta
    {
        return Conta::query()->firstWhere('numero_conta', $numeroConta);
    }
}
