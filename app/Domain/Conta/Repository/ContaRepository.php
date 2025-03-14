<?php declare(strict_types=1);

namespace App\Domain\Conta\Repository;

use App\Domain\Conta\Conta;

final class ContaRepository
{
    public function findByNumeroConta(int $numeroConta): ?Conta
    {
        return Conta::query()->firstWhere('numero_conta', $numeroConta);
    }

    public function create(int $numeroConta, float $saldo): Conta
    {
        $conta = new Conta();
        $conta->numero_conta = $numeroConta;
        $conta->saldo = $saldo;
        $conta->save();

        return $conta;
    }
}
