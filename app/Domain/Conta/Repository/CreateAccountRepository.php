<?php

namespace App\Domain\Conta\Repository;

use App\Domain\Conta\Conta;

interface CreateAccountRepository
{
    public function handle(int $numeroConta, float $saldo): Conta;
}
