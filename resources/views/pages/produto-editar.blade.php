@extends('layouts.app', ['title' => 'Editar Produto | VittaSys'])

@section('content')
<div class="mx-auto max-w-4xl p-5">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold">Editar produto</h2>
        <a href="{{ route('web.produtos.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Voltar</a>
    </div>

    <section class="rounded-xl bg-white p-6 shadow-sm">
        <form action="{{ route('web.produtos.update', $produto) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="nome" class="mb-1 block text-sm font-medium">Nome</label>
                    <input id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if ($hasSku)
                    <div>
                        <label for="sku" class="mb-1 block text-sm font-medium">SKU</label>
                        <input id="sku" name="sku" value="{{ old('sku', $produto->sku) }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                        @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="lote" class="mb-1 block text-sm font-medium">Lote</label>
                    <input id="lote" name="lote" value="{{ old('lote', $produto->lote_atual) }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('lote')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="validade" class="mb-1 block text-sm font-medium">Validade</label>
                    <input id="validade" name="validade" type="date" value="{{ old('validade', $produto->validade_atual) }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('validade')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="preco_custo" class="mb-1 block text-sm font-medium">Preco de custo (R$)</label>
                    <input id="preco_custo" name="preco_custo" type="number" step="0.01" value="{{ old('preco_custo', $produto->preco_custo_atual) }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('preco_custo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="quantidade" class="mb-1 block text-sm font-medium">Quantidade</label>
                    <input id="quantidade" name="quantidade" type="number" value="{{ old('quantidade', $produto->estoque_atual) }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('quantidade')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="categoria" class="mb-1 block text-sm font-medium">Categoria existente</label>
                    <select id="categoria" name="categoria" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2">
                        <option value="">Selecione...</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected(old('categoria', $produto->categoria) === $categoria)>{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="categoria_nova" class="mb-1 block text-sm font-medium">Ou nova categoria</label>
                    <input id="categoria_nova" name="categoria_nova" value="{{ old('categoria_nova') }}" placeholder="Digite nova categoria se quiser trocar" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('categoria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('categoria_nova')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="descricao" class="mb-1 block text-sm font-medium">Descricao</label>
                <textarea id="descricao" name="descricao" class="min-h-[100px] w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2">{{ old('descricao', $produto->descricao) }}</textarea>
                @error('descricao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="imagem" class="mb-1 block text-sm font-medium">Imagem do produto</label>
                <div class="mb-2">
                    <img src="{{ $produto->imagem_url }}" alt="Imagem de {{ $produto->nome }}" class="h-36 w-full max-w-xs rounded-md object-cover" />
                </div>
                <input id="imagem" name="imagem" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                <p class="mt-1 text-xs text-slate-500">Envie uma nova imagem para substituir a atual (max. 2MB).</p>
                @error('imagem')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('web.produtos.index') }}" class="rounded-md border border-slate-300 px-4 py-2">Cancelar</a>
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-white">Salvar alteracoes</button>
            </div>
        </form>
    </section>
</div>
@endsection
