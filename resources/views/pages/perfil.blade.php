@extends('layouts.app', ['title' => 'Perfil | VittaSys'])

@section('content')
<div class="mx-auto max-w-4xl p-6 lg:p-8">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
        <h1 class="text-2xl font-extrabold text-ink">Perfil</h1>
        <p class="mt-1 text-sm text-slate-500">Informações da conta autenticada.</p>

        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nome</dt>
                <dd class="mt-1 text-base font-semibold text-ink">{{ auth()->user()->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">E-mail</dt>
                <dd class="mt-1 text-base font-semibold text-ink">{{ auth()->user()->email }}</dd>
            </div>
        </dl>
    </section>
</div>
@endsection
