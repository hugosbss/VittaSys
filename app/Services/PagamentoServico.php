<?php

namespace App\Services;

use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PagamentoServico
{
    public function __construct(
        private readonly InventarioService $inventarioService,
    ) {
    }

    /**
     * 
     */
    public function finalize(array $dados): Venda
    {
        return $this->finalizar($dados);
    }

    public function finalizar(array $dados): Venda
    {
        $this->validarDadosMinimos($dados);

        return DB::transaction(function () use ($dados) {
            $itensEntrada = $dados['itens'] ?? $dados['items'] ?? [];
            $pagamentosEntrada = $dados['pagamentos'] ?? $dados['payments'] ?? [];
            $desconto = (float) ($dados['desconto'] ?? 0);

            $formaPagamento = (string) (
                $dados['forma_pagamento']
                ?? $dados['payment_method']
                ?? ($pagamentosEntrada[0]['metodo'] ?? $pagamentosEntrada[0]['method'] ?? 'dinheiro')
            );

            $venda = Venda::create([
                'user_id' => (int) $dados['user_id'],
                'caixa_id' => (int) $dados['caixa_id'],
                'status' => 'finalizada',
                'forma_pagamento' => $formaPagamento,
                'total_bruto' => 0,
                'desconto' => 0,
                'total_liquido' => 0,
                'created_at' => now(),
            ]);

            $totalBruto = 0.0;

            foreach ($itensEntrada as $item) {
                $produtoId = (int) ($item['produto_id'] ?? $item['product_id'] ?? 0);
                $quantidadeSolicitada = (int) ($item['quantidade'] ?? $item['quantity'] ?? 0);

                if ($produtoId <= 0 || $quantidadeSolicitada <= 0) {
                    throw new InvalidArgumentException('Item inválido informado para a venda.');
                }

                $precoUnitarioItem = $item['preco_unitario'] ?? $item['unit_price'] ?? null;
                $precoUnitarioItem = $precoUnitarioItem !== null ? (float) $precoUnitarioItem : null;

                $movimentos = $this->inventarioService->baixarEstoque(
                    $produtoId,
                    $quantidadeSolicitada,
                    $precoUnitarioItem,
                );

                foreach ($movimentos as $movimento) {
                    $venda->itens()->create([
                        'produto_id' => $produtoId,
                        'lote_produto_id' => $movimento['lote_produto_id'],
                        'quantidade' => $movimento['quantidade'],
                        'preco_unitario' => $movimento['preco_unitario'],
                        'subtotal' => $movimento['subtotal'],
                        'created_at' => now(),
                    ]);
                    $totalBruto += $movimento['subtotal'];
                }
            }

            $desconto = max(0, round($desconto, 2));
            if ($desconto > $totalBruto) {
                throw new InvalidArgumentException('Desconto não pode ser maior que o total bruto.');
            }

            $totalLiquido = round($totalBruto - $desconto, 2);

            $totalPagamentos = 0.0;
            foreach ($pagamentosEntrada as $pagamento) {
                $metodo = (string) ($pagamento['metodo'] ?? $pagamento['method'] ?? '');
                $valor = round((float) ($pagamento['valor'] ?? $pagamento['amount'] ?? 0), 2);

                if ($metodo === '' || $valor <= 0) {
                    throw new InvalidArgumentException('Pagamento inválido informado para a venda.');
                }

                $venda->pagamentos()->create([
                    'metodo' => $metodo,
                    'valor' => $valor,
                    'created_at' => now(),
                ]);

                $totalPagamentos += $valor;
            }

            $totalPagamentos = round($totalPagamentos, 2);
            if ($formaPagamento !== 'crediario' && $pagamentosEntrada !== [] && abs($totalPagamentos - $totalLiquido) > 0.01) {
                throw new InvalidArgumentException('Soma dos pagamentos difere do total líquido da venda.');
            }

            $venda->update([
                'total_bruto' => round($totalBruto, 2),
                'desconto' => $desconto,
                'total_liquido' => $totalLiquido,
            ]);

            return $venda->load(['itens', 'pagamentos']);
        });
    }

    private function validarDadosMinimos(array $dados): void
    {
        if (empty($dados['user_id'])) {
            throw new InvalidArgumentException('user_id é obrigatório.');
        }

        if (empty($dados['caixa_id'])) {
            throw new InvalidArgumentException('caixa_id é obrigatório.');
        }

        $itens = $dados['itens'] ?? $dados['items'] ?? [];
        if ($itens === []) {
            throw new InvalidArgumentException('A venda precisa conter ao menos 1 item.');
        }
    }

    public function processarPagamentoOnline(float $amount, string $currency = 'BRL'): array
    {
        $stripeService = new StripePaymentService();
        $paymentIntent = $stripeService->createPaymentIntent($amount, $currency);

        if (!$paymentIntent || !isset($paymentIntent->client_secret, $paymentIntent->id)) {
            throw new RuntimeException('Falha ao criar o pagamento online.');
        }

        return [
            'client_secret' => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id,
        ];
    }
}
