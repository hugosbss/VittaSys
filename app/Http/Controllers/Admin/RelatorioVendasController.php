<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RelatorioVendasController extends Controller
{
    public function imprimir(Request $request): View
    {
        $filters = (array) $request->query('tableFilters', []);

        $status = $this->filterValue($filters, 'status', 'status');
        $formaPagamento = $this->filterValue($filters, 'forma_pagamento', 'forma_pagamento');
        $produtoId = $this->filterValue($filters, 'produto_id', 'produto_id');
        $de = data_get($filters, 'periodo.de', $request->query('de'));
        $ate = data_get($filters, 'periodo.ate', $request->query('ate'));

        $query = Venda::query()
            ->with(['vendedor', 'itens.produto', 'pagamentos'])
            ->when($status, fn (Builder $q) => $q->where('status', $status))
            ->when($formaPagamento, fn (Builder $q) => $q->where('forma_pagamento', $formaPagamento))
            ->when($de, fn (Builder $q) => $q->whereDate('created_at', '>=', $de))
            ->when($ate, fn (Builder $q) => $q->whereDate('created_at', '<=', $ate))
            ->when($produtoId, function (Builder $q) use ($produtoId): Builder {
                return $q->whereHas('itens', fn (Builder $itensQuery) => $itensQuery->where('produto_id', $produtoId));
            })
            ->orderByDesc('created_at');

        $vendas = $query->get();

        $resumo = [
            'total_vendas' => $vendas->count(),
            'total_bruto' => (float) $vendas->sum('total_bruto'),
            'total_desconto' => (float) $vendas->sum('desconto'),
            'total_liquido' => (float) $vendas->sum('total_liquido'),
        ];

        $produtoNome = $produtoId ? Produto::query()->whereKey($produtoId)->value('nome') : null;

        return view('admin.relatorios.vendas-impressao', [
            'vendas' => $vendas,
            'resumo' => $resumo,
            'filtros' => [
                'status' => $status,
                'forma_pagamento' => $formaPagamento,
                'produto' => $produtoNome,
                'de' => $de,
                'ate' => $ate,
            ],
        ]);
    }

    private function filterValue(array $filters, string $key, string $fallbackKey): mixed
    {
        return data_get($filters, "{$key}.value", request()->query($fallbackKey));
    }
}
