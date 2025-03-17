<?php

declare(strict_types=1);

namespace App\Domain\Transacao\Services;

use App\Domain\Conta\Conta;
use App\Domain\Conta\Repository\ContaRepository;
use App\Domain\Support\Exceptions\DomainException;
use App\Domain\Support\Exceptions\NotFoundException;
use App\Domain\Support\Exceptions\ValidationException;
use App\Domain\Transacao\FormaPagamento;
use App\Domain\Transacao\Transacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

final class CriarTransacaoService
{
    public function __construct(private ContaRepository $contaRepository) {}

    /**
     * @throws ValidationException
     * @throws Throwable
     * @throws NotFoundException
     * @throws DomainException
     */
    public function execute(array $data): Transacao
    {
        $this->validate($data);

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

        $this->atualizarSaldo($conta, $transacao);

        return $transacao;
    }

    /**
     * @throws ValidationException
     */
    private function validate(array $data): void
    {
        $validator = Validator::make($data, [
            'numero_conta' => 'required|integer|min:0',
            'forma_pagamento' => Rule::enum(FormaPagamento::class),
            'valor' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }
    }

    /**
     * @throws Throwable
     */
    private function atualizarSaldo(Conta $conta, Transacao $transacao): void
    {
        DB::transaction(function () use ($transacao, $conta) {
            $transacao->save();
            $conta->saldo -= $transacao->calcularValorEfetivo();
            $conta->save();
        });
    }
}
