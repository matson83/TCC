<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LogicController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Rota GET para exibir o formulário de upload
Route::get('/', function () {
    return view('welcome');
})->name('transcricao.form');


// Rota POST para processar o upload e transcrição
Route::get('/lista', [LogicController::class, 'index'])->name('transcricao.index');

// Rota POST para processar o upload e transcrição
Route::post('/', [LogicController::class, 'store'])->name('transcricao.store');

Route::post('/transcricao/ajustar', [ChatController::class, 'ajustarPontuacao']);
