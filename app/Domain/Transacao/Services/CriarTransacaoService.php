<?php declare(strict_types=1);

namespace App\Domain\Transacao\Services;

use App\Domain\Conta\Repository\ContaRepository;
use App\Domain\Support\Exceptions\DomainException;
use App\Domain\Support\Exceptions\NotFoundException;
use App\Domain\Support\Exceptions\ValidationException;
use App\Domain\Transacao\Transacao;
use Illuminate\Support\Facades\Validator;

final class CriarTransacaoService
{
    public function __construct(
        private ContaRepository $contaRepository
    ) {}

    /**
     * @throws ValidationException
     * @throws DomainException
     */
    public function execute(array $data): Transacao
    {
        $validator = Validator::make($data, [
            'numero_conta' => 'required|integer|min:0',
            'valor' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        $conta = $this->contaRepository->findByNumeroConta($data['numero_conta']);

        if (is_null($conta)) {
            throw new NotFoundException('Conta não encontrada.');
        }
        if ($conta->saldo < $data['valor']) {
            throw new NotFoundException('Saldo insuficiente.');
        }

        //TODO Abrir uma transação no banco de dados...

        //TODO Repository
        $transacao = new Transacao();
        $transacao->valor = $data['valor'];
        $transacao->conta()->associate($conta);
        $transacao->save();

        $valor = $data['valor'];
        $taxa = 0.03; //TODO calcular a taxa
        $valorFinal = $valor + ($valor * $taxa);

        $conta->saldo -= $valorFinal;
        $conta->save();

        return $transacao;
    }
}
