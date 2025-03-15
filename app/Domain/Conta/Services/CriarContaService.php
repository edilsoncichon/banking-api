<?php

declare(strict_types=1);

namespace App\Domain\Conta\Services;

use App\Domain\Conta\Conta;
use App\Domain\Conta\Repository\ContaRepository;
use App\Domain\Support\Exceptions\DomainException;
use App\Domain\Support\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;

final class CriarContaService
{
    public function __construct(private ContaRepository $contaRepository) {}

    /**
     * @throws ValidationException
     * @throws DomainException
     */
    public function execute(array $data): Conta
    {
        $validator = Validator::make($data, [
            'numero_conta' => 'required|integer|min:0',
            'saldo' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        $conta = $this->contaRepository->findByNumeroConta($data['numero_conta']);

        if ($conta) {
            throw new DomainException('Conta já existe.');
        }

        return $this->contaRepository->create((int) $data['numero_conta'], (float) $data['saldo']);
    }
}
