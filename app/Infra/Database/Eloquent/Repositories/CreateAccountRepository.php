<?php

declare(strict_types=1);

namespace App\Infra\Database\Eloquent\Repositories;

use App\Domain\Conta\Conta;
use App\Domain\Conta\Repository\CreateAccountRepository as CreateAccountRepositoryContract;

final class CreateAccountRepository implements CreateAccountRepositoryContract
{
    public function handle(int $numeroConta, float $saldo): Conta
    {
        $conta = new Conta;
        $conta->numero_conta = $numeroConta;
        $conta->saldo = $saldo;
        $conta->save();

        return $conta;
    }
}
