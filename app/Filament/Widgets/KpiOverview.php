<?php

namespace App\Filament\Widgets;

use App\Models\ItemVendido;
use App\Models\Venda;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KpiOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $inicioMes = now()->startOfMonth();

        $vendasMes = Venda::query()
            ->where('status', 'finalizada')
            ->where('created_at', '>=', $inicioMes);

        $qtdVendas = (int) (clone $vendasMes)->count();
        $faturamentoMes = (float) (clone $vendasMes)->sum('total_liquido');
        $ticketMedio = $qtdVendas > 0 ? $faturamentoMes / $qtdVendas : 0;

        $itensVendidosMes = (int) ItemVendido::query()
            ->whereHas('venda', fn ($q) => $q->where('status', 'finalizada')->where('created_at', '>=', $inicioMes))
            ->sum('quantidade');

        $produtosPorVenda = $qtdVendas > 0
            ? round($itensVendidosMes / $qtdVendas, 2)
            : 0;

        return [
            Stat::make('Faturamento no mês', 'R$ ' . number_format($faturamentoMes, 2, ',', '.'))
                ->description('Vendas finalizadas no mês atual')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Total de vendas', (string) $qtdVendas)
                ->description('Quantidade de vendas finalizadas')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('primary'),
            // Stat::make('Ticket médio', 'R$ ' . number_format($ticketMedio, 2, ',', '.'))
            //     ->description('Média por venda no mês')
            //     ->descriptionIcon('heroicon-m-chart-bar')
            //     ->color('warning'),
            Stat::make('Itens vendidos no mês', (string) $itensVendidosMes)
                ->description('Soma de quantidades vendidas')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
            Stat::make('Produtos por venda', number_format($produtosPorVenda, 2, ',', '.'))
                ->description('Média de itens por venda')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('gray'),
        ];
    }
}
