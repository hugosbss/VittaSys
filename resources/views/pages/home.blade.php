@extends('layouts.app', ['title' => 'Home | VittaSys'])

@section('content')
<div class="mx-auto flex max-w-7xl flex-col gap-7 p-7">
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid gap-6 rounded-2xl bg-surface p-7 shadow-soft lg:grid-cols-[1.2fr_0.8fr]">
        <div>
            <span class="inline-flex rounded-full bg-teal-100 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.04em] text-brandDark">Farmacia Online</span>
            <h1 class="mt-3 text-4xl font-extrabold leading-tight">Gestao de produtos e estoque.</h1>
            <p class="mt-2 max-w-xl text-slate-500">Visualize os produtos em tempo real, filtre por categoria e acompanhe precos com um dashboard simples e elegante.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                @auth
                    <a href="{{ route('web.produtos.index') }}" class="rounded-full bg-ink px-5 py-2.5 font-bold text-white">Ver produtos</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-full bg-ink px-5 py-2.5 font-bold text-white">Entrar</a>
                    <a href="{{ route('register') }}" class="rounded-full border border-slate-300 px-5 py-2.5 font-bold text-ink">Criar conta</a>
                @endauth
            </div>
        </div>

        <div class="grid gap-3">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <h3 class="text-sm font-semibold text-slate-500">Produtos</h3>
                <p class="mt-2 text-2xl font-extrabold">128</p>
                <span class="text-sm text-slate-500">Total cadastrados</span>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <h3 class="text-sm font-semibold text-slate-500">Destaques</h3>
                <p class="mt-2 text-2xl font-extrabold">5</p>
                <span class="text-sm text-slate-500">Selecionados</span>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <h3 class="text-sm font-semibold text-slate-500">Catalogo</h3>
                <p class="mt-2 text-2xl font-extrabold">Atualizado</p>
                <span class="text-sm text-slate-500">Dados em tempo real</span>
            </div>
        </div>
    </section>

    <section class="rounded-2xl bg-surface p-6 shadow-soft">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold">Produtos em destaque</h2>
                <p class="text-slate-500">Explore alguns dos itens mais recentes do catalogo.</p>
            </div>
            @auth
                <a href="{{ route('web.produtos.index') }}" class="rounded-full border border-slate-300 px-4 py-2 font-semibold text-ink">Ver todos</a>
            @else
                <a href="{{ route('login') }}" class="rounded-full border border-slate-300 px-4 py-2 font-semibold text-ink">Entrar para ver</a>
            @endauth
        </div>
    </section>
</div>
@endsection
