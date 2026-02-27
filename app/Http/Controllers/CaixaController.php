<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Produto;
use App\Services\PagamentoServico;
use App\Services\ReportService;
use App\Services\SaldoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CaixaController extends Controller
{
    public function __construct(
        private readonly PagamentoServico $pagamentoServico,
        private readonly ReportService $reportService,
        private readonly SaldoService $saldoService,
    ) {
    }

    public function index(Request $request): View
    {
        $usuario = $request->user();
        abort_unless($usuario !== null, 401);

        $caixa = Caixa::query()
            ->where('user_id', $usuario->id)
            ->where('status', 'aberto')
            ->latest('id')
            ->first();

        if (!$caixa) {
            $caixa = Caixa::create([
                'user_id' => $usuario->id,
                'valor_abertura' => 0,
                'aberto_em' => now(),
                'status' => 'aberto',
            ]);
        }

        return view('pdv.caixa', [
            'caixaId' => $caixa->id,
            'resumoCaixa' => $this->reportService->gerarRelatorio($caixa->id),
        ]);
    }

    public function finalizarVenda(Request $request): JsonResponse
    {
        $usuario = $request->user();
        abort_unless($usuario !== null, 401);

        $dados = $request->validate([
            'caixa_id' => ['nullable', 'integer', 'exists:caixas,id'],
            'desconto' => ['nullable', 'numeric', 'min:0'],
            'forma_pagamento' => ['nullable', 'string', 'in:dinheiro,pix,credito,debito,crediario'],
            'itens' => ['required', 'array', 'min:1'],
            'itens.*.produto_id' => ['required', 'integer', 'exists:produtos,id'],
            'itens.*.quantidade' => ['required', 'integer', 'min:1'],
            'itens.*.preco_unitario' => ['nullable', 'numeric', 'min:0'],
            'pagamentos' => ['nullable', 'array'],
            'pagamentos.*.metodo' => ['required_with:pagamentos', 'string', 'in:dinheiro,pix,credito,debito'],
            'pagamentos.*.valor' => ['required_with:pagamentos', 'numeric', 'gt:0'],
        ]);

        $caixa = Caixa::query()
            ->where('user_id', $usuario->id)
            ->where('status', 'aberto')
            ->when(isset($dados['caixa_id']), function ($query) use ($dados) {
                $query->where('id', $dados['caixa_id']);
            })
            ->latest('id')
            ->first();

        if (!$caixa) {
            return response()->json([
                'message' => 'Nenhum caixa aberto encontrado para o usuário.',
            ], 422);
        }

        $dados['user_id'] = $usuario->id;
        $dados['caixa_id'] = $caixa->id;

        $venda = $this->pagamentoServico->finalizar($dados);

        return response()->json([
            'message' => 'Venda finalizada com sucesso.',
            'venda' => $venda,
            'saldo_atual' => $this->saldoService->calcularSaldoCaixa($caixa->id),
            'resumo_caixa' => $this->reportService->gerarRelatorio($caixa->id),
        ], 201);
    }

    public function resumo(Request $request): JsonResponse
    {
        $usuario = $request->user();
        abort_unless($usuario !== null, 401);

        $caixa = Caixa::query()
            ->where('user_id', $usuario->id)
            ->where('status', 'aberto')
            ->latest('id')
            ->first();

        if (!$caixa) {
            return response()->json([
                'message' => 'Nenhum caixa aberto encontrado para o usuário.',
            ], 404);
        }

        return response()->json($this->reportService->gerarRelatorio($caixa->id));
    }

    public function buscarProdutos(Request $request): JsonResponse
    {
        $usuario = $request->user();
        abort_unless($usuario !== null, 401);

        $termo = trim((string) $request->query('termo', ''));
        if ($termo === '') {
            return response()->json(['produtos' => []]);
        }

        $hasSku = Schema::hasColumn('produtos', 'sku');

        $produtos = Produto::query()
            ->with('loteAtual')
            ->whereHas('lotes', function ($query) {
                $query->where('quantidade', '>', 0);
            })
            ->where(function ($query) use ($termo, $hasSku) {
                $query->where('nome', 'like', "%{$termo}%");

                if (is_numeric($termo)) {
                    $query->orWhere('id', (int) $termo);
                }

                if ($hasSku) {
                    $query->orWhere('sku', 'like', "%{$termo}%");
                }
            })
            ->limit(20)
            ->get()
            ->map(function (Produto $produto) {
                return [
                    'id' => $produto->id,
                    'nome' => $produto->nome,
                    'sku' => $produto->sku ?? null,
                    'lote_atual' => $produto->lote_atual,
                    'validade_atual' => $produto->validade_atual,
                    'validade_atual_formatada' => $produto->validade_atual_formatada,
                    'preco_custo_atual' => (float) ($produto->preco_custo_atual ?? 0),
                    'estoque_atual' => (int) $produto->estoque_atual,
                ];
            })
            ->values();

        return response()->json([
            'produtos' => $produtos,
        ]);
    }
}
