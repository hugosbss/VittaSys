@extends('layouts.app', ['title' => 'Home | VittaSys'])

@section('content')
<div class="mx-auto max-w-7xl p-6 lg:p-8">
    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-soft lg:p-10">
        <div class="pointer-events-none absolute -right-20 -top-20 h-60 w-60 rounded-full bg-teal-100/60 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-amber-100/60 blur-2xl"></div>

        <div class="relative grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
            <div>
                <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.08em] text-brandDark">Painel de Operações</span>
                <h1 class="mt-4 text-4xl font-extrabold leading-tight text-ink lg:text-5xl">Central de gestão da operação</h1>
                <p class="mt-3 max-w-xl text-slate-600">Acesse rapidamente os módulos do sistema para gerenciamento de produtos, operação de caixa e futuros relatórios administrativos.</p>

                @guest
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="rounded-full bg-ink px-5 py-2.5 text-sm font-bold text-white">Entrar</a>
                        <a href="{{ route('register') }}" class="rounded-full border border-slate-300 px-5 py-2.5 text-sm font-bold text-ink">Criar conta</a>
                    </div>
                @endguest
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Módulos ativos</p>
                    <p class="mt-2 text-3xl font-extrabold text-ink">3</p>
                    <p class="mt-1 text-xs text-slate-500">Gestão, Caixa e Admin</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Integração</p>
                    <p class="mt-2 text-3xl font-extrabold text-ink">Online</p>
                    <p class="mt-1 text-xs text-slate-500">Dados conectados ao banco</p>
                </div>
                <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status do ambiente</p>
                    <p class="mt-2 text-lg font-bold text-emerald-700">Pronto para operação</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-7 grid gap-4 md:grid-cols-3">
        <a href="{{ route('web.produtos.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-soft transition hover:-translate-y-0.5 hover:shadow-lg">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gestão</p>
            <h2 class="mt-2 text-xl font-extrabold text-ink">Produtos</h2>
            <p class="mt-2 text-sm text-slate-600">Cadastre, edite e consulte produtos e lotes.</p>
            <span class="mt-4 inline-block text-sm font-bold text-brand group-hover:text-brandDark">Abrir módulo →</span>
        </a>

        <a href="{{ url('/admin') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-soft transition hover:-translate-y-0.5 hover:shadow-lg">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Relatórios</p>
            <h2 class="mt-2 text-xl font-extrabold text-ink">Admin</h2>
            <p class="mt-2 text-sm text-slate-600">Área reservada para dashboards, planilhas e relatórios completos.</p>
            <span class="mt-4 inline-block text-sm font-bold text-brand group-hover:text-brandDark">Acessar /admin →</span>
        </a>

        <a href="{{ route('caixa') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-soft transition hover:-translate-y-0.5 hover:shadow-lg">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Caixa</p>
            <h2 class="mt-2 text-xl font-extrabold text-ink">PDV</h2>
            <p class="mt-2 text-sm text-slate-600">Abra a frente de caixa para operação de venda.</p>
            <span class="mt-4 inline-block text-sm font-bold text-brand group-hover:text-brandDark">Abrir caixa →</span>
        </a>
    </section>
</div>
@endsection
