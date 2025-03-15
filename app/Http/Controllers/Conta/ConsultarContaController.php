<?php declare(strict_types=1);

namespace App\Http\Controllers\Conta;

use App\Domain\Conta\Repository\ContaRepository;
use App\Http\Resources\ContaResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsultarContaController
{
    public function __invoke(Request $request, ContaRepository $repository)
    {
        $conta = $repository->findByNumeroConta((int) $request->query('numero_conta'));

        if (is_null($conta)) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return ContaResource::make($conta);
    }
}
