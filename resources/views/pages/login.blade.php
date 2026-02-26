@extends('layouts.app', ['title' => 'Login | VittaSys'])

@section('content')
<div class="flex min-h-[calc(100vh-80px)] items-center justify-center p-6">
    <div class="w-full max-w-md rounded-2xl bg-surface p-7 shadow-soft">
        <h2 class="text-2xl font-bold">Entrar</h2>
        <p class="mb-3 text-slate-500">Acesse sua conta para gerenciar produtos.</p>

        <form action="{{ route('login.store') }}" method="POST" class="space-y-3">
            @csrf

            <div>
                <label for="email" class="mb-1 block text-sm font-semibold">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="voce@email.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 outline-none" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-semibold">Senha</label>
                <input id="password" name="password" type="password" placeholder="Sua senha" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 outline-none" />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="mt-2 w-full rounded-full bg-ink py-3 font-bold text-white">Entrar</button>
            <p class="text-center text-sm">Nao tem conta? <a href="{{ route('register') }}" class="font-semibold text-brandDark">Criar agora</a></p>
        </form>
    </div>
</div>
@endsection
