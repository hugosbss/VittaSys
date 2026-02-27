@extends('layouts.app', ['title' => 'Produtos | VittaSys'])

@section('content')
<div class="mx-auto max-w-7xl space-y-4 p-5">
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('web.produtos.index') }}" class="space-y-4">
        <section class="flex flex-wrap items-center gap-2">
            <h2 class="mr-2 text-2xl font-bold">Gerenciamento de Produtos</h2>
            <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Buscar produto..." class="min-w-[220px] flex-1 rounded-lg border border-slate-300 px-3 py-2" />
            <button type="submit" class="rounded-lg bg-blue-700 px-4 py-2 font-semibold text-white">Buscar</button>
            <a href="{{ route('web.produtos.cadastrar') }}" class="rounded-lg bg-emerald-700 px-4 py-2 font-semibold text-white">Novo produto</a>
        </section>

        <section class="grid gap-3 rounded-xl bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-6">
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500">Categoria</label>
                <select name="categoria" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="">Todas</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria }}" @selected($filters['categoria'] === $categoria)>{{ $categoria }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500">Preco min.</label>
                <input type="number" step="0.01" name="min_preco" value="{{ $filters['min_preco'] }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="0" />
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500">Preco max.</label>
                <input type="number" step="0.01" name="max_preco" value="{{ $filters['max_preco'] }}" class="w-full rounded-lg border border-slate-300 px-3 py-2" placeholder="200" />
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500">Ordenar por</label>
                <select name="sort_by" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="created_at" @selected($filters['sort_by'] === 'created_at')>Mais recentes</option>
                    <option value="nome" @selected($filters['sort_by'] === 'nome')>Nome</option>
                    <option value="preco_custo" @selected($filters['sort_by'] === 'preco_custo')>Preco de custo</option>
                    <option value="quantidade" @selected($filters['sort_by'] === 'quantidade')>Estoque atual</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500">Direcao</label>
                <select name="sort_dir" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="desc" @selected($filters['sort_dir'] === 'desc')>Desc</option>
                    <option value="asc" @selected($filters['sort_dir'] === 'asc')>Asc</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500">Por pagina</label>
                <select name="per_page" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="5" @selected($filters['per_page'] === '5')>5</option>
                    <option value="10" @selected($filters['per_page'] === '10')>10</option>
                    <option value="20" @selected($filters['per_page'] === '20')>20</option>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-ink px-4 py-2 font-semibold text-white">Aplicar filtros</button>
            <a href="{{ route('web.produtos.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center font-semibold text-ink">Limpar filtros</a>
        </section>
    </form>

    <section class="overflow-x-auto rounded-xl bg-white p-5 shadow-sm">
        <form action="{{ route('web.produtos.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="mb-3 flex items-center justify-between gap-2">
                <p class="text-sm text-slate-500">Marque os produtos e use a acao em lote.</p>
                <button type="submit" class="rounded bg-red-700 px-3 py-2 text-sm font-semibold text-white">Excluir selecionados</button>
            </div>

            <table class="w-full min-w-[980px] border-collapse text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="border-b border-slate-200 p-3">
                        <input id="selectAllProdutos" type="checkbox" title="Selecionar todos" />
                    </th>
                    <th class="border-b border-slate-200 p-3">ID</th>
                    <th class="border-b border-slate-200 p-3">Imagem</th>
                    <th class="border-b border-slate-200 p-3">Nome</th>
                    @if ($hasSku)
                        <th class="border-b border-slate-200 p-3">SKU</th>
                    @endif
                    <th class="border-b border-slate-200 p-3">Lote</th>
                    <th class="border-b border-slate-200 p-3">Validade</th>
                    <th class="border-b border-slate-200 p-3">Preco custo</th>
                    <th class="border-b border-slate-200 p-3">Estoque</th>
                    <th class="border-b border-slate-200 p-3">Categoria</th>
                    <th class="border-b border-slate-200 p-3">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produtos as $produto)
                    <tr>
                        <td class="border-b border-slate-100 p-3">
                            <input class="produto-checkbox" type="checkbox" name="ids[]" value="{{ $produto->id }}" />
                        </td>
                        <td class="border-b border-slate-100 p-3">{{ $produto->id }}</td>
                        <td class="border-b border-slate-100 p-3">
                            <img src="{{ $produto->imagem_url }}" alt="Imagem de {{ $produto->nome }}" class="h-12 w-12 rounded object-cover" />
                        </td>
                        <td class="border-b border-slate-100 p-3">{{ $produto->nome }}</td>
                        @if ($hasSku)
                            <td class="border-b border-slate-100 p-3">{{ $produto->sku ?: '-' }}</td>
                        @endif
                        <td class="border-b border-slate-100 p-3">{{ $produto->lote_atual ?: '-' }}</td>
                        <td class="border-b border-slate-100 p-3">{{ $produto->validade_atual_formatada ?: '-' }}</td>
                        <td class="border-b border-slate-100 p-3">R$ {{ number_format((float) $produto->preco_custo_atual, 2, ',', '.') }}</td>
                        <td class="border-b border-slate-100 p-3">{{ $produto->estoque_atual }}</td>
                        <td class="border-b border-slate-100 p-3">{{ $produto->categoria ?: 'Sem categoria' }}</td>
                        <td class="border-b border-slate-100 p-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('web.produtos.show', $produto) }}" class="rounded bg-sky-600 px-3 py-1.5 text-white">Abrir</a>
                                <a href="{{ route('web.produtos.edit', $produto) }}" class="rounded bg-amber-600 px-3 py-1.5 text-white">Editar</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $hasSku ? 11 : 10 }}" class="p-4 text-center text-slate-500">Nenhum produto encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </form>

        <div class="mt-4">
            {{ $produtos->links() }}
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('selectAllProdutos');
    const checkboxes = document.querySelectorAll('.produto-checkbox');

    if (!selectAll) return;

    selectAll.addEventListener('change', () => {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAll.checked;
        });
    });
});
</script>
@endsection
