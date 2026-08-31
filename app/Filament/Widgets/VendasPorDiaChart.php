<?php

namespace App\Filament\Widgets;

use App\Models\Venda;
use Filament\Widgets\ChartWidget;

class VendasPorDiaChart extends ChartWidget
{
    protected ?string $heading = 'Vendas diárias (últimos 15 dias)';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $valores = [];

        for ($i = 14; $i >= 0; $i--) {
            $dia = now()->startOfDay()->subDays($i);

            $labels[] = $dia->format('d/m');
            $valores[] = (float) Venda::query()
                ->where('status', 'finalizada')
                ->whereDate('created_at', $dia->toDateString())
                ->sum('total_liquido');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Faturamento diário',
                    'data' => $valores,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
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
