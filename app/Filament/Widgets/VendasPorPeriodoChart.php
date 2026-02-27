<?php

namespace App\Filament\Widgets;

use App\Models\Venda;
use Filament\Widgets\ChartWidget;

class VendasPorPeriodoChart extends ChartWidget
{
    protected ?string $heading = 'Vendas (últimos 6 meses)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $labels = [];
        $valores = [];

        for ($i = 5; $i >= 0; $i--) {
            $data = now()->startOfMonth()->subMonths($i);

            $labels[] = $data->format('m/Y');
            $valores[] = (float) Venda::query()
                ->where('status', 'finalizada')
                ->whereYear('created_at', $data->year)
                ->whereMonth('created_at', $data->month)
                ->sum('total_liquido');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Faturamento',
                    'data' => $valores,
                    'borderColor' => '#0ea5a4',
                    'backgroundColor' => 'rgba(14, 165, 164, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
