<?php

use App\Http\Controllers\Conta\ConsultarContaController;
use App\Http\Controllers\Conta\CriarContaController;
use App\Http\Controllers\Transacao\CriarTransacaoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Banking API',
    ]);
});

Route::get('/conta', ConsultarContaController::class);
Route::post('/conta', CriarContaController::class);
Route::post('/transacao', CriarTransacaoController::class);
