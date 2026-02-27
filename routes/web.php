<?php

use App\Http\Controllers\CaixaController;
use App\Http\Controllers\Admin\RelatorioVendasController;
use App\Http\Controllers\Admin\RelatorioListagemController;
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

    Route::view('/perfil', 'pages.perfil')->name('perfil');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
    Route::get('/caixa', [CaixaController::class, 'index'])->name('caixa');
    Route::post('/caixa/finalizar', [CaixaController::class, 'finalizarVenda'])->name('caixa.finalizar');
    Route::get('/caixa/resumo', [CaixaController::class, 'resumo'])->name('caixa.resumo');
    Route::get('/caixa/produtos/buscar', [CaixaController::class, 'buscarProdutos'])->name('caixa.buscar-produtos');
    Route::get('/admin/relatorios/vendas/imprimir', [RelatorioVendasController::class, 'imprimir'])->name('admin.relatorios.vendas.imprimir');
    Route::get('/admin/relatorios/movimentos/imprimir', [RelatorioListagemController::class, 'imprimir'])->name('admin.relatorios.movimentos.imprimir');
});
