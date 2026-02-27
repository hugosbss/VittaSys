<?php

namespace App\Services;

use App\Models\LoteProduto;
use RuntimeException;

class InventarioService
{
    /**
     * 
     *
     * @return array<int
     */
    public function atualizarEstoque(int $produtoId, int $quantidade, ?float $precoUnitario = null): array
    {
        return $this->baixarEstoque($produtoId, $quantidade, $precoUnitario);
    }

    /**
     * 
     *
     * @return array<int
     */
    public function baixarEstoque(int $produtoId, int $quantidade, ?float $precoUnitario = null): array
    {
        $quantidadeRestante = max(0, $quantidade);
        $movimentos = [];

        $lotes = LoteProduto::query()
            ->where('produto_id', $produtoId)
            ->where('quantidade', '>', 0)
            ->orderBy('validade')
            ->lockForUpdate()
            ->get();

        foreach ($lotes as $lote) {
            if ($quantidadeRestante <= 0) {
                break;
            }

            $consumida = min($quantidadeRestante, (int) $lote->quantidade);
            if ($consumida <= 0) {
                continue;
            }

            $precoAplicado = $precoUnitario ?? (float) $lote->preco_custo;
            $subtotal = round($precoAplicado * $consumida, 2);

            $lote->decrement('quantidade', $consumida);

            $movimentos[] = [
                'lote_produto_id' => (int) $lote->id,
                'quantidade' => $consumida,
                'preco_unitario' => $precoAplicado,
                'subtotal' => $subtotal,
            ];

            $quantidadeRestante -= $consumida;
        }

        if ($quantidadeRestante > 0) {
            throw new RuntimeException("Estoque insuficiente para produto {$produtoId}.");
        }

        return $movimentos;
    }
}
