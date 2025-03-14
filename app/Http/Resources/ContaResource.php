<?php

namespace App\Http\Resources;

use App\Domain\Conta\Conta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Conta
 */
class ContaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'numero_conta' => $this->numero_conta,
            'saldo' => $this->saldo,
        ];
    }
}
