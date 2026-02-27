@props(['mode' => 'default'])

@php
    $isFixed = in_array($mode, ['home', 'produtos'], true);
    $navClass = $isFixed
        ? 'fixed top-0 left-0 right-0 z-50 transition-transform duration-300'
        : 'relative z-10';
@endphp

<nav id="mainNavbar" data-navbar-mode="{{ $mode }}" class="{{ $navClass }} border-b border-slate-200/80 bg-white/80 px-6 py-4 backdrop-blur">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-4 lg:grid-cols-[auto_1fr_auto] lg:items-center">
        <a href="{{ route('home') }}" class="inline-flex flex-col leading-none">
            <span class="text-[1.1rem] font-extrabold text-ink">VittaSys</span>
            <span class="text-xs font-semibold uppercase tracking-[0.08em] text-brand">Pharma</span>
        </a>

        <form action="{{ route('web.produtos.index') }}" method="GET" class="flex items-center gap-2 rounded-full border border-slate-200 bg-surface px-3 py-1.5 shadow-soft">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar produtos, vitaminas, medicamentos..."
                class="w-full min-w-0 bg-transparent px-2 py-1 text-sm text-ink outline-none lg:min-w-[240px]" />
            <button type="submit" class="rounded-full bg-brand px-3.5 py-2 text-sm font-semibold text-white">Buscar</button>
        </form>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('home') }}" class="text-sm font-semibold text-ink">Home</a>
            @auth
                {{-- <a href="{{ route('web.produtos.index') }}" class="text-sm font-semibold text-ink">Produtos</a>
                <a href="{{ route('caixa') }}" class="text-sm font-semibold text-ink">Caixa</a> --}}

                <details class="relative">
                    <summary class="list-none cursor-pointer rounded-full border border-slate-300 bg-white px-3 py-1.5">
                        <span class="flex items-center gap-2">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-slate-100 text-slate-700">
                                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M20 22C20 18.6863 16.4183 16 12 16C7.58172 16 4 18.6863 4 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span class="max-w-[120px] truncate text-sm font-semibold text-ink">{{ auth()->user()->name }}</span>
                        </span>
                    </summary>

                    <div class="absolute right-0 mt-2 w-44 rounded-xl border border-slate-200 bg-white p-2 shadow-soft">
                        <a href="{{ route('perfil') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Perfil</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-rose-700 hover:bg-rose-50">Sair</button>
                        </form>
                    </div>
                </details>
            @else
                <a href="{{ route('login') }}" class="rounded-full border border-slate-300 px-3.5 py-2 text-sm font-bold text-ink">Entrar</a>
                <a href="{{ route('register') }}" class="rounded-full bg-ink px-3.5 py-2 text-sm font-bold text-white">Criar conta</a>
            @endauth
        </div>
    </div>
</nav>
