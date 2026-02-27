<?php

namespace App\Services;

use App\Models\Pagamento;
use App\Models\Venda;

class ReportService
{
    public function __construct(private readonly SaldoService $saldoService) 
    {
        //
    }

    public function gerarRelatorio(int $caixaId): array
    {
        $vendasFinalizadas = Venda::query()
            ->where('caixa_id', $caixaId)
            ->where('status', 'finalizada');

        $totalVendas = (float) (clone $vendasFinalizadas)->sum('total_liquido');
        $quantidadeVendas = (int) (clone $vendasFinalizadas)->count();
        $totalBruto = (float) (clone $vendasFinalizadas)->sum('total_bruto');
        $totalDescontos = (float) (clone $vendasFinalizadas)->sum('desconto');

        $totalPagamentos = (float) Pagamento::query()
            ->whereHas('venda', function ($query) use ($caixaId) {
                $query->where('caixa_id', $caixaId)
                    ->where('status', 'finalizada');
            })
            ->sum('valor');

        $ticketMedio = $quantidadeVendas > 0
            ? round($totalVendas / $quantidadeVendas, 2)
            : 0.0;

        return [
            'caixa_id' => $caixaId,
            'saldo_atual' => $this->saldoService->calcularSaldoCaixa($caixaId),
            'total_vendas' => round($totalVendas, 2),
            'total_pagamentos' => round($totalPagamentos, 2),
            'quantidade_vendas' => $quantidadeVendas,
            'ticket_medio' => $ticketMedio,
            'total_bruto' => round($totalBruto, 2),
            'total_descontos' => round($totalDescontos, 2),
        ];
    }
}
