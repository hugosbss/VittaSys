@extends('layouts.app', ['title' => 'Logout | VittaSys'])

@section('content')
<div class="flex min-h-[calc(100vh-80px)] items-center justify-center p-6">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-soft">
        <h2 class="text-2xl font-bold">Logout</h2>
        <p class="mt-2 text-slate-500">Tela placeholder para fluxo de logout (equivalente ao componente Angular sem template).</p>
        <div class="mt-5">
            <a href="{{ route('login') }}" class="rounded-full bg-ink px-5 py-2.5 font-semibold text-white">Voltar para login</a>
        </div>
    </div>
</div>
@endsection
