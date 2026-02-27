<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório de Movimentos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #1f2937; }
        h1 { margin: 0 0 6px; }
        .muted { color: #64748b; font-size: 12px; }
        .grid { display: grid; grid-template-columns: repeat(3, minmax(120px, 1fr)); gap: 12px; margin: 16px 0; }
        .card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 8px; font-size: 12px; text-align: left; }
        th { background: #f8fafc; }
        .actions { margin-top: 12px; }
        @media print { .actions { display: none; } body { margin: 8px; } }
    </style>
</head>
<body>
    <h1>Relatório de movimentos</h1>
    <p class="muted">Gerado em {{ now()->format('d/m/Y H:i') }}</p>

    <p class="muted">
        Filtros:
        Status da venda: {{ $filtros['status_venda'] ?: 'Todos' }} |
        Período: {{ $filtros['de'] ?: '-' }} até {{ $filtros['ate'] ?: '-' }}
    </p>

    <div class="grid">
        <div class="card"><strong>Movimentos</strong><br>{{ $resumo['total_movimentos'] }}</div>
        <div class="card"><strong>Itens movimentados</strong><br>{{ $resumo['total_itens'] }}</div>
        <div class="card"><strong>Valor total</strong><br>R$ {{ number_format($resumo['total_valor'], 2, ',', '.') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mov.</th>
                <th>Venda</th>
                <th>Data</th>
                <th>Produto</th>
                <th>Lote</th>
                <th>Qtd</th>
                <th>Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movimentos as $movimento)
                <tr>
                    <td>#{{ $movimento->id }}</td>
                    <td>#{{ $movimento->venda_id }} ({{ $movimento->venda?->status }})</td>
                    <td>{{ $movimento->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $movimento->produto?->nome }}</td>
                    <td>{{ $movimento->loteProduto?->lote }}</td>
                    <td>{{ $movimento->quantidade }}</td>
                    <td>R$ {{ number_format((float) $movimento->preco_unitario, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format((float) $movimento->subtotal, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Nenhum movimento encontrado para os filtros informados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="actions">
        <button onclick="window.print()">Imprimir</button>
    </div>
</body>
</html>
