<?php

namespace App\Filament\Widgets;

use App\Models\Venda;
use Filament\Widgets\ChartWidget;

class FormasPagamentoChart extends ChartWidget
{
    protected ?string $heading = 'Distribuição por forma de pagamento';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $base = Venda::query()
            ->where('status', 'finalizada')
            ->where('created_at', '>=', now()->startOfMonth());

        $dados = [
            'Dinheiro' => (clone $base)->where('forma_pagamento', 'dinheiro')->count(),
            'Pix' => (clone $base)->where('forma_pagamento', 'pix')->count(),
            'Crédito' => (clone $base)->where('forma_pagamento', 'credito')->count(),
            'Débito' => (clone $base)->where('forma_pagamento', 'debito')->count(),
            'Crediário' => (clone $base)->where('forma_pagamento', 'crediario')->count(),
        ];

        return [
            'datasets' => [
                [
                    'data' => array_values($dados),
                    'backgroundColor' => ['#0ea5a4', '#22c55e', '#3b82f6', '#f59e0b', '#8b5cf6'],
                ],
            ],
            'labels' => array_keys($dados),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
