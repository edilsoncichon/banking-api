<?php declare(strict_types=1);

namespace App\Http\Controllers\Conta;

use App\Domain\Conta\Services\CriarContaService;
use App\Domain\Support\Exceptions\DomainException;
use App\Domain\Support\Exceptions\ValidationException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContaResource;
use Illuminate\Http\Request;

class CriarContaController extends Controller
{
    public function __invoke(Request $request, CriarContaService $service)
    {
        try {
            $conta = $service->execute($request->all());
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->getErrors()], 422);
        }

        return ContaResource::make($conta);
    }
}
