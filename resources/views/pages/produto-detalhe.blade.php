@extends('layouts.app', ['title' => 'Detalhes do Produto | VittaSys'])

@section('content')
<div class="mx-auto max-w-4xl p-5">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold">Detalhes do produto</h2>
        <a href="{{ route('web.produtos.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Voltar</a>
    </div>

    <section class="rounded-xl bg-white p-6 shadow-sm">
        <div class="mb-6">
            <img src="{{ $produto->imagem_url }}" alt="Imagem de {{ $produto->nome }}" class="h-56 w-full rounded-lg object-cover sm:w-80" />
        </div>

        <dl class="grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">ID</dt>
                <dd class="mt-1 text-base font-medium text-slate-900">{{ $produto->id }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nome</dt>
                <dd class="mt-1 text-base font-medium text-slate-900">{{ $produto->nome }}</dd>
            </div>
            @if ($hasSku)
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">SKU</dt>
                    <dd class="mt-1 text-base font-medium text-slate-900">{{ $produto->sku ?: '-' }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Categoria</dt>
                <dd class="mt-1 text-base font-medium text-slate-900">{{ $produto->categoria ?: 'Sem categoria' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Preco</dt>
                <dd class="mt-1 text-base font-medium text-slate-900">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estoque</dt>
                <dd class="mt-1 text-base font-medium text-slate-900">{{ $produto->quantidade_estoque }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Descricao</dt>
                <dd class="mt-1 text-base font-medium text-slate-900">{{ $produto->descricao ?: 'Sem descricao.' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Criado em</dt>
                <dd class="mt-1 text-base font-medium text-slate-900">{{ $produto->created_at?->format('d/m/Y H:i') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Atualizado em</dt>
                <dd class="mt-1 text-base font-medium text-slate-900">{{ $produto->updated_at?->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </section>
</div>
@endsection
