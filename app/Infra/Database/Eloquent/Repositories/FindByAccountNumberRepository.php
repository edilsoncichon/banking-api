<?php

declare(strict_types=1);

namespace App\Infra\Database\Eloquent\Repositories;

use App\Domain\Conta\Conta;
use App\Domain\Conta\Repository\FindByAccountNumberRepository as FindByAccountNumberRepositoryContract;

final class FindByAccountNumberRepository implements FindByAccountNumberRepositoryContract
{
    public function handle(int $numeroConta): ?Conta
    {
        return Conta::query()->firstWhere('numero_conta', $numeroConta);
    }
}
