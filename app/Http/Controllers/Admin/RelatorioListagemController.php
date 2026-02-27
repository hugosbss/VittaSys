<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemVendido;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RelatorioListagemController extends Controller
{
    public function imprimir(Request $request): View
    {
        $filters = (array) $request->query('tableFilters', []);

        $statusVenda = $this->filterValue($filters, 'status_venda', 'status_venda');
        $de = data_get($filters, 'periodo.de', $request->query('de'));
        $ate = data_get($filters, 'periodo.ate', $request->query('ate'));

        $query = ItemVendido::query()
            ->with(['venda.vendedor', 'produto', 'loteProduto'])
            ->when($statusVenda, function (Builder $q) use ($statusVenda): Builder {
                return $q->whereHas('venda', fn (Builder $vendaQuery) => $vendaQuery->where('status', $statusVenda));
            })
            ->when($de, fn (Builder $q) => $q->whereDate('created_at', '>=', $de))
            ->when($ate, fn (Builder $q) => $q->whereDate('created_at', '<=', $ate))
            ->orderByDesc('created_at');

        $movimentos = $query->get();

        $resumo = [
            'total_movimentos' => $movimentos->count(),
            'total_itens' => (int) $movimentos->sum('quantidade'),
            'total_valor' => (float) $movimentos->sum('subtotal'),
        ];

        return view('admin.relatorios.movimentos-impressao', [
            'movimentos' => $movimentos,
            'resumo' => $resumo,
            'filtros' => [
                'status_venda' => $statusVenda,
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
