<?php

namespace App\Services;

use App\Models\Caixa;
use App\Models\Venda;

class SaldoService
{
    public function calcularSaldoCaixa(int $caixaId): float
    {
        $caixa = Caixa::query()->find($caixaId);
        if (!$caixa) {
            return 0.0;
        }

        $totalVendas = (float) Venda::query()
            ->where('caixa_id', $caixaId)
            ->where('status', 'finalizada')
            ->sum('total_liquido');

        return round(((float) $caixa->valor_abertura) + $totalVendas, 2);
    }
}
