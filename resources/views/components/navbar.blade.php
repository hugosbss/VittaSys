<nav class="sticky top-0 z-10 border-b border-slate-200/80 bg-white/80 backdrop-blur px-6 py-4">
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
                <a href="{{ route('web.produtos.index') }}" class="text-sm font-semibold text-ink">Produtos</a>
                <a href="{{ route('web.produtos.cadastrar') }}" class="text-sm font-semibold text-ink">Cadastrar</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full border border-slate-300 px-3.5 py-2 text-sm font-bold text-ink">Sair</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-full border border-slate-300 px-3.5 py-2 text-sm font-bold text-ink">Entrar</a>
                <a href="{{ route('register') }}" class="rounded-full bg-ink px-3.5 py-2 text-sm font-bold text-white">Criar conta</a>
            @endauth
        </div>
    </div>
</nav>
