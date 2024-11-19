<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LogicController;
use App\Http\Controllers\ProfileController;

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [ProfileController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__ . '/auth.php';
