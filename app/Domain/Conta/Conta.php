<?php declare(strict_types=1);

namespace App\Domain\Conta;

use App\Domain\Transacao\Transacao;
use Database\Factories\ContaFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property int numero_conta
 * @property float saldo
 * @property Collection<Transacao> transacoes
 */
class Conta extends Model
{
    use HasFactory;

    protected $table = 'conta';

    protected $casts = [
        'numero_conta' => 'integer',
        'saldo' => 'float',
    ];

    public function transacoes(): HasMany
    {
        return $this->hasMany(Transacao::class);
    }

    protected static function newFactory()
    {
        return ContaFactory::new();
    }
}
