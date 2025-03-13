<?php declare(strict_types=1);

namespace App\Domain\Transacao;

use App\Domain\Conta\Conta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property float valor
 * @property FormaPagamento forma_pagamento
 * @property Conta conta
 */
class Transacao extends Model
{
    protected $table = 'transacao';

    protected $casts = [
        'forma_pagamento' => FormaPagamento::class,
    ];

    public function conta(): BelongsTo
    {
        return $this->belongsTo(Conta::class);
    }
}
