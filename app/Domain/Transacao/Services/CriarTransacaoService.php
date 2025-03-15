<?php

declare(strict_types=1);

namespace App\Domain\Transacao\Services;

use App\Domain\Conta\Repository\ContaRepository;
use App\Domain\Support\Exceptions\DomainException;
use App\Domain\Support\Exceptions\NotFoundException;
use App\Domain\Support\Exceptions\ValidationException;
use App\Domain\Transacao\FormaPagamento;
use App\Domain\Transacao\Transacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class CriarTransacaoService
{
    public function __construct(private ContaRepository $contaRepository) {}

    /**
     * @throws ValidationException
     * @throws DomainException
     * @throws \Throwable
     */
    public function execute(array $data): Transacao
    {
        $validator = Validator::make($data, [
            'numero_conta' => 'required|integer|min:0',
            'forma_pagamento' => Rule::enum(FormaPagamento::class),
            'valor' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        $conta = $this->contaRepository->findByNumeroConta((int) $data['numero_conta']);

        if (is_null($conta)) {
            throw new NotFoundException('Conta não encontrada.');
        }

        $transacao = new Transacao;
        $transacao->forma_pagamento = FormaPagamento::from($data['forma_pagamento']);
        $transacao->conta()->associate($conta);
        $transacao->valor = $data['valor'];

        if ($conta->saldo < $transacao->calcularValorEfetivo()) {
            throw new DomainException('Saldo insuficiente.');
        }

        DB::transaction(function () use ($transacao, $conta) {
            $transacao->save();
            $conta->saldo -= $transacao->calcularValorEfetivo();
            $conta->save();
        });

        return $transacao;
    }
}
