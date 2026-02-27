<?php

namespace App\Filament\Widgets;

use App\Models\ItemVendido;
use Filament\Widgets\ChartWidget;

class ProdutosMaisVendidosChart extends ChartWidget
{
    protected ?string $heading = 'Top 10 produtos mais vendidos';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $topProdutos = ItemVendido::query()
            ->selectRaw('produto_id, SUM(quantidade) as total_quantidade')
            ->whereHas('venda', fn ($q) => $q->where('status', 'finalizada'))
            ->with('produto:id,nome')
            ->groupBy('produto_id')
            ->orderByDesc('total_quantidade')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Quantidade vendida',
                    'data' => $topProdutos->pluck('total_quantidade')->all(),
                    'backgroundColor' => '#0ea5a4',
                ],
            ],
            'labels' => $topProdutos->map(fn ($item) => $item->produto?->nome ?? 'Produto')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
