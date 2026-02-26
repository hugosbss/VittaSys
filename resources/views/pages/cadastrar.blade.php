@extends('layouts.app', ['title' => 'Cadastrar Produto | VittaSys'])

@section('content')
<div class="mx-auto max-w-6xl p-5">
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-5">
        <h2 class="text-2xl font-bold">Cadastro de Produtos</h2>
    </div>

    <section class="mb-6 rounded-xl bg-white p-5 shadow-md">
        <form action="{{ route('web.produtos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="nome" class="mb-1 block text-sm font-medium">Nome</label>
                    <input id="nome" name="nome" value="{{ old('nome') }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if ($hasSku)
                    <div>
                        <label for="sku" class="mb-1 block text-sm font-medium">SKU</label>
                        <input id="sku" name="sku" value="{{ old('sku') }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                        @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="preco" class="mb-1 block text-sm font-medium">Preco (R$)</label>
                    <input id="preco" name="preco" type="number" step="0.01" value="{{ old('preco') }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('preco')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="quantidade_estoque" class="mb-1 block text-sm font-medium">Estoque</label>
                    <input id="quantidade_estoque" name="quantidade_estoque" type="number" value="{{ old('quantidade_estoque', 0) }}" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                    @error('quantidade_estoque')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="categoria" class="mb-1 block text-sm font-medium">Categoria</label>
                    <select id="categoria" name="categoria" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2">
                        <option value="">Selecione...</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected(old('categoria') === $categoria)>{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="categoria_nova" class="mb-1 block text-sm font-medium">Ou nova categoria</label>
                    <input id="categoria_nova" name="categoria_nova" value="{{ old('categoria_nova') }}" placeholder="Digite nova categoria se nao existir" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
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
                <textarea id="descricao" name="descricao" class="min-h-[80px] w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2">{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="imagem" class="mb-1 block text-sm font-medium">Imagem do produto</label>
                <input id="imagem" name="imagem" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2" />
                <p class="mt-1 text-xs text-slate-500">Formatos: JPG, PNG, WEBP (max. 2MB)</p>
                @error('imagem')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col justify-center gap-3 sm:flex-row">
                <button type="submit" class="rounded-md bg-green-700 px-5 py-2.5 font-semibold text-white">Salvar</button>
                <a href="{{ route('web.produtos.index') }}" class="rounded-md bg-slate-200 px-5 py-2.5 text-center font-semibold text-slate-700">Ver produtos</a>
            </div>
        </form>
    </section>

    <section class="rounded-xl bg-white p-5 shadow-md">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h3 class="text-xl font-semibold">Ultimos cadastrados</h3>
            <a href="{{ route('web.produtos.index') }}" class="rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white">Ver todos</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($ultimosProdutos as $produto)
                <a href="{{ route('web.produtos.show', $produto) }}" class="block rounded-lg bg-white p-4 shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <h4 class="font-semibold">{{ $produto->nome }}</h4>
                        <span class="font-bold text-green-700">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</span>
                    </div>
                    <img src="{{ $produto->imagem_url }}" alt="Imagem de {{ $produto->nome }}" class="mb-2 h-28 w-full rounded-md object-cover" />
                    <p class="mb-2 text-sm text-slate-600">{{ $produto->descricao ?: 'Sem descricao.' }}</p>
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $produto->categoria ?: 'Sem categoria' }}</span>
                        <span>Estoque: {{ $produto->quantidade_estoque }}</span>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-500">Nenhum produto cadastrado ainda.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
