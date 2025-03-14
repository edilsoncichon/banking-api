<?php

use App\Http\Controllers\Conta\CriarContaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Banking API',
    ]);
});

Route::post('/conta', CriarContaController::class);
