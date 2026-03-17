<?php

namespace Database\Seeders;

use App\Models\Caixa;
use App\Models\ItemVendido;
use App\Models\Pagamento;
use App\Models\Produto;
use App\Models\User;
use App\Services\PagamentoServico;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class VendasSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'admin@vittasys.com')->first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@vittasys.com',
                'password' => bcrypt('123456'),
            ]);
        }

        $pagamentoServico = app(PagamentoServico::class);
        $faker = Factory::create('pt_BR');

        $inicio = Carbon::now()->subMonths(11)->startOfMonth();

        for ($mesIndex = 0; $mesIndex < 12; $mesIndex++) {
            $mes = (clone $inicio)->addMonths($mesIndex);
            $abertura = $faker->randomFloat(2, 50, 300);
            $caixa = Caixa::create([
                'user_id' => $user->id,
                'valor_abertura' => $abertura,
                'aberto_em' => (clone $mes)->setTime(9, 0, 0),
                'status' => $mes->isSameMonth(Carbon::now()) ? 'aberto' : 'fechado',
            ]);

            $totalCaixa = 0.0;
            $vendasNoMes = $faker->numberBetween(8, 18);

            for ($i = 0; $i < $vendasNoMes; $i++) {
                $dataVenda = (clone $mes)
                    ->addDays($faker->numberBetween(0, $mes->daysInMonth - 1))
                    ->setTime($faker->numberBetween(9, 20), $faker->numberBetween(0, 59), $faker->numberBetween(0, 59));

                $itens = [];
                $itensCount = $faker->numberBetween(1, 3);

                for ($itemIndex = 0; $itemIndex < $itensCount; $itemIndex++) {
                    $produto = Produto::query()
                        ->whereHas('lotes', fn ($q) => $q->where('quantidade', '>', 0))
                        ->inRandomOrder()
                        ->first();

                    if (!$produto) {
                        break 2;
                    }

                    $itens[] = [
                        'produto_id' => $produto->id,
                        'quantidade' => $faker->numberBetween(1, 3),
                        'preco_unitario' => $faker->randomFloat(2, 8, 90),
                    ];
                }

                $formaPagamento = $faker->randomElement(['dinheiro', 'pix', 'credito', 'debito']);
                $dados = [
                    'user_id' => $user->id,
                    'caixa_id' => $caixa->id,
                    'forma_pagamento' => $formaPagamento,
                    'desconto' => 0,
                    'itens' => $itens,
                    'pagamentos' => [],
                ];

                $venda = $pagamentoServico->finalizar($dados);

                $totalLiquido = (float) $venda->total_liquido;
                $venda->pagamentos()->create([
                    'metodo' => $formaPagamento,
                    'valor' => $totalLiquido,
                    'created_at' => $dataVenda,
                ]);

                $venda->update(['created_at' => $dataVenda]);
                ItemVendido::query()->where('venda_id', $venda->id)->update(['created_at' => $dataVenda]);
                Pagamento::query()->where('venda_id', $venda->id)->update(['created_at' => $dataVenda]);

                $totalCaixa += $totalLiquido;
            }

            if ($caixa->status === 'fechado') {
                $caixa->update([
                    'fechado_em' => (clone $mes)->endOfMonth()->setTime(18, 0, 0),
                    'valor_fechamento' => round($abertura + $totalCaixa, 2),
                ]);
            }
        }
    }
}
