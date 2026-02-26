<?php

use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\WebProdutoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.store');

    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/produtos', [WebProdutoController::class, 'index'])->name('web.produtos.index');
    Route::get('/produtos/cadastrar', [WebProdutoController::class, 'create'])->name('web.produtos.cadastrar');
    Route::post('/produtos', [WebProdutoController::class, 'store'])->name('web.produtos.store');
    Route::delete('/produtos', [WebProdutoController::class, 'bulkDestroy'])->name('web.produtos.bulk-destroy');
    Route::get('/produtos/{produto}', [WebProdutoController::class, 'show'])->name('web.produtos.show');
    Route::get('/produtos/{produto}/editar', [WebProdutoController::class, 'edit'])->name('web.produtos.edit');
    Route::put('/produtos/{produto}', [WebProdutoController::class, 'update'])->name('web.produtos.update');
    Route::delete('/produtos/{produto}', [WebProdutoController::class, 'destroy'])->name('web.produtos.destroy');

    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
});
