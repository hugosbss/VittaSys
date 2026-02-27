<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório de Vendas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #1f2937; }
        h1 { margin: 0 0 6px; }
        .muted { color: #64748b; font-size: 12px; }
        .grid { display: grid; grid-template-columns: repeat(4, minmax(120px, 1fr)); gap: 12px; margin: 16px 0; }
        .card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 8px; font-size: 12px; text-align: left; }
        th { background: #f8fafc; }
        .actions { margin-top: 12px; }
        @media print { .actions { display: none; } body { margin: 8px; } }
    </style>
</head>
<body>
    <h1>Relatório de vendas</h1>
    <p class="muted">Gerado em {{ now()->format('d/m/Y H:i') }}</p>

    <p class="muted">
        Filtros:
        Status: {{ $filtros['status'] ?: 'Todos' }} |
        Pagamento: {{ $filtros['forma_pagamento'] ?: 'Todos' }} |
        Produto: {{ $filtros['produto'] ?: 'Todos' }} |
        Período: {{ $filtros['de'] ?: '-' }} até {{ $filtros['ate'] ?: '-' }}
    </p>

    <div class="grid">
        <div class="card"><strong>Vendas</strong><br>{{ $resumo['total_vendas'] }}</div>
        <div class="card"><strong>Total bruto</strong><br>R$ {{ number_format($resumo['total_bruto'], 2, ',', '.') }}</div>
        <div class="card"><strong>Total desconto</strong><br>R$ {{ number_format($resumo['total_desconto'], 2, ',', '.') }}</div>
        <div class="card"><strong>Total líquido</strong><br>R$ {{ number_format($resumo['total_liquido'], 2, ',', '.') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Venda</th>
                <th>Data</th>
                <th>Vendedor</th>
                <th>Pagamento</th>
                <th>Status</th>
                <th>Líquido</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendas as $venda)
                <tr>
                    <td>#{{ $venda->id }}</td>
                    <td>{{ $venda->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $venda->vendedor?->name }}</td>
                    <td>{{ $venda->forma_pagamento }}</td>
                    <td>{{ $venda->status }}</td>
                    <td>R$ {{ number_format((float) $venda->total_liquido, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhum dado encontrado para os filtros informados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="actions">
        <button onclick="window.print()">Imprimir</button>
    </div>
</body>
</html>
