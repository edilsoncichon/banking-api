<?php

declare(strict_types=1);

namespace App\Http\Controllers\Transacao;

use App\Domain\Support\Exceptions\DomainException;
use App\Domain\Support\Exceptions\NotFoundException;
use App\Domain\Support\Exceptions\ValidationException;
use App\Domain\Transacao\Services\CriarTransacaoService;
use App\Http\Resources\ContaResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CriarTransacaoController
{
    public function __invoke(Request $request, CriarTransacaoService $service)
    {
        try {
            $transacao = $service->execute($request->all());
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->getErrors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return ContaResource::make($transacao->conta)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
