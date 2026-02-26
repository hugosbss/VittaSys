@extends('layouts.app', ['title' => 'Cadastro | VittaSys'])

@section('content')
<div class="flex min-h-[calc(100vh-80px)] items-center justify-center p-6">
    <div class="w-full max-w-md rounded-2xl bg-surface p-7 shadow-soft">
        <h2 class="text-2xl font-bold">Criar conta</h2>
        <p class="mb-3 text-slate-500">Registre-se para acessar o sistema.</p>

        <form action="{{ route('register.store') }}" method="POST" class="space-y-3">
            @csrf

            <div>
                <label for="name" class="mb-1 block text-sm font-semibold">Nome</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Seu nome" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 outline-none" />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-semibold">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="voce@email.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 outline-none" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-semibold">Senha</label>
                <input id="password" name="password" type="password" placeholder="Crie uma senha" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 outline-none" />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-semibold">Confirmar senha</label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repita a senha" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 outline-none" />
            </div>

            <button type="submit" class="mt-2 w-full rounded-full bg-ink py-3 font-bold text-white">Criar conta</button>
            <p class="text-center text-sm">Ja possui conta? <a href="{{ route('login') }}" class="font-semibold text-brandDark">Entrar</a></p>
        </form>
    </div>
</div>
@endsection
