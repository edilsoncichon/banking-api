<?php

namespace App\Domain\Conta\Repository;

use App\Domain\Conta\Conta;

interface FindByAccountNumberRepository
{
    public function handle(int $numeroConta): ?Conta;
}
