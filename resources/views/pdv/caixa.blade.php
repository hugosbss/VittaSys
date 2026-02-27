@extends('layouts.app', ['title' => 'Caixa | VittaSys', 'hideNavbar' => true])

@section('content')
<div class="mx-auto max-w-[1500px] p-4 lg:p-6" x-data="pdvCaixa()" x-init="init()">
    <header class="mb-4 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-soft">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-brand/15 text-brand grid place-items-center font-extrabold">PDV</div>
                <div>
                    <h1 class="text-xl font-extrabold text-ink">Frente de Caixa</h1>
                    <p class="text-xs text-slate-500">Operador: Admin</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                <div class="rounded-lg bg-slate-50 px-3 py-2 text-xs">
                    <p class="text-slate-500">Data</p>
                    <p class="font-bold" x-text="statusData"></p>
                </div>
                <div class="rounded-lg bg-slate-50 px-3 py-2 text-xs">
                    <p class="text-slate-500">Hora</p>
                    <p class="font-bold" x-text="statusHora"></p>
                </div>
                <div class="rounded-lg bg-slate-50 px-3 py-2 text-xs">
                    <p class="text-slate-500">Status</p>
                    <p class="font-bold" :class="caixaAberto ? 'text-emerald-700' : 'text-rose-700'" x-text="caixaAberto ? 'Caixa aberto' : 'Caixa fechado'"></p>
                </div>
                <div class="rounded-lg bg-ink px-3 py-2 text-xs text-white">
                    <p class="text-slate-300">Total da venda</p>
                    <p class="text-lg font-extrabold">R$ <span x-text="dinheiro(totalLiquido)"></span></p>
                </div>
            </div>
        </div>
    </header>

    <div class="grid gap-4 xl:grid-cols-[1.8fr_1fr]">
        <section class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-soft">
                <div class="grid gap-3 md:grid-cols-[1.4fr_1fr_1fr_auto]">
                    <input type="text" x-model="codigoBarras" placeholder="Código de barras" class="rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" x-model="buscaNome" placeholder="Buscar por nome" class="rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" x-model="codigoInterno" placeholder="Código interno" class="rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                    <button type="button" class="rounded-xl bg-brand px-4 py-2 text-sm font-bold text-white">Adicionar</button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-soft">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-slate-600">Itens da venda</h2>
                    <p class="text-xs text-slate-500">Itens lidos: <strong x-text="itens.length"></strong></p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-slate-600">
                            <tr>
                                <th class="px-3 py-2">Item</th>
                                <th class="px-3 py-2">Lote</th>
                                <th class="px-3 py-2">Qtd</th>
                                <th class="px-3 py-2">Unitário</th>
                                <th class="px-3 py-2">Subtotal</th>
                                <th class="px-3 py-2">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, idx) in itens" :key="idx">
                                <tr class="border-t border-slate-100">
                                    <td class="px-3 py-2" x-text="item.descricao"></td>
                                    <td class="px-3 py-2" x-text="item.lote"></td>
                                    <td class="px-3 py-2">
                                        <input type="number" min="1" x-model.number="item.quantidade" @input="recalcularItem(item)" class="w-16 rounded-lg border border-slate-300 px-2 py-1" />
                                    </td>
                                    <td class="px-3 py-2">R$ <span x-text="dinheiro(item.preco_unitario)"></span></td>
                                    <td class="px-3 py-2 font-semibold">R$ <span x-text="dinheiro(item.subtotal)"></span></td>
                                    <td class="px-3 py-2">
                                        <button type="button" @click="removerItem(idx)" class="rounded-lg bg-rose-600 px-2 py-1 text-xs font-bold text-white">Cancelar</button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="itens.length === 0">
                                <td colspan="6" class="px-3 py-6 text-center text-slate-500">Nenhum item na venda</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold">F1 Iniciar Venda</button>
                <button type="button" class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold">F3 Consulta Preço</button>
                <button type="button" class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold">F6 Cancelar Item/Desconto</button>
                <button type="button" class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700">F8 Cancelar Venda</button>
                <button type="button" @click="mostrarPainel = !mostrarPainel" class="rounded-lg bg-ink px-3 py-2 text-xs font-semibold text-white">Mais ações</button>
            </div>
        </section>

        <aside class="space-y-4">
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-soft">
                <h3 class="mb-3 text-xs font-bold uppercase tracking-wide text-slate-500">Fechamento</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Total bruto</span><strong>R$ <span x-text="dinheiro(totalBruto)"></span></strong></div>
                    <div class="flex items-center justify-between gap-3">
                        <span>Desconto</span>
                        <input type="number" min="0" step="0.01" x-model.number="desconto" class="w-24 rounded-lg border border-slate-300 px-2 py-1" />
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-bold">
                        <span>Total líquido</span>
                        <span class="text-emerald-700">R$ <span x-text="dinheiro(totalLiquido)"></span></span>
                    </div>
                </div>

                <h4 class="mb-2 mt-4 text-xs font-bold uppercase tracking-wide text-slate-500">Pagamento</h4>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" @click="formaPagamento = 'dinheiro'" :class="classeForma('dinheiro')" class="rounded-lg border px-3 py-2">Dinheiro</button>
                    <button type="button" @click="formaPagamento = 'pix'" :class="classeForma('pix')" class="rounded-lg border px-3 py-2">Pix</button>
                    <button type="button" @click="formaPagamento = 'credito'" :class="classeForma('credito')" class="rounded-lg border px-3 py-2">Crédito</button>
                    <button type="button" @click="formaPagamento = 'debito'" :class="classeForma('debito')" class="rounded-lg border px-3 py-2">Débito</button>
                    <button type="button" @click="formaPagamento = 'crediario'" :class="classeForma('crediario')" class="col-span-2 rounded-lg border px-3 py-2">Crediário</button>
                </div>

                <div class="mt-3" x-show="formaPagamento === 'dinheiro'">
                    <label class="mb-1 block text-xs font-semibold text-slate-500">Valor recebido</label>
                    <input type="number" step="0.01" min="0" x-model.number="valorRecebido" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <p class="mt-2 text-sm">Troco: <strong class="text-emerald-700">R$ <span x-text="dinheiro(troco)"></span></strong></p>
                </div>

                <button type="button" class="mt-4 w-full rounded-xl bg-emerald-700 px-4 py-3 text-sm font-extrabold text-white">F4 Finalizar Venda</button>
            </section>
        </aside>
    </div>

    <section x-show="mostrarPainel" x-transition class="mt-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-soft">
        <div class="grid gap-4 lg:grid-cols-3">
            <article class="rounded-xl border border-sky-200 bg-sky-50 p-3">
                <h4 class="font-bold text-sky-800">Emissão fiscal</h4>
                <p class="mt-1 text-xs text-sky-700">Cupom fiscal (NFC-e/SAT) e nota fiscal eletrônica integrada.</p>
            </article>
            <article class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                <h4 class="font-bold text-emerald-800">Controle de estoque</h4>
                <p class="mt-1 text-xs text-emerald-700">Baixa automática de estoque direto no banco após confirmar a venda.</p>
            </article>
            <article class="rounded-xl border border-violet-200 bg-violet-50 p-3">
                <h4 class="font-bold text-violet-800">Relatórios rápidos</h4>
                <p class="mt-1 text-xs text-violet-700">Vendas por período, ticket médio, lucro bruto e fechamento de caixa.</p>
            </article>
        </div>
    </section>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function pdvCaixa() {
    return {
        statusData: '',
        statusHora: '',
        caixaAberto: true,
        mostrarPainel: false,
        codigoBarras: '',
        buscaNome: '',
        codigoInterno: '',
        desconto: 0,
        valorRecebido: 0,
        formaPagamento: 'dinheiro',
        itens: [
            { descricao: 'Dipirona 500mg', lote: 'DP-2102', quantidade: 2, preco_unitario: 8.50, subtotal: 17.00 },
            { descricao: 'Vitamina C 1g', lote: 'VC-9981', quantidade: 1, preco_unitario: 24.90, subtotal: 24.90 },
        ],

        init() {
            this.atualizarHorario();
            setInterval(() => this.atualizarHorario(), 1000);
        },

        atualizarHorario() {
            const agora = new Date();
            this.statusData = agora.toLocaleDateString('pt-BR');
            this.statusHora = agora.toLocaleTimeString('pt-BR');
        },

        recalcularItem(item) {
            item.quantidade = Math.max(1, Number(item.quantidade || 1));
            item.subtotal = item.quantidade * Number(item.preco_unitario || 0);
        },

        removerItem(index) {
            this.itens.splice(index, 1);
        },

        dinheiro(valor) {
            return Number(valor || 0).toFixed(2).replace('.', ',');
        },

        classeForma(metodo) {
            return this.formaPagamento === metodo
                ? 'border-brand bg-teal-50 text-teal-800 font-semibold'
                : 'border-slate-300 bg-white text-slate-700';
        },

        get totalBruto() {
            return this.itens.reduce((total, item) => total + Number(item.subtotal || 0), 0);
        },

        get totalLiquido() {
            const total = this.totalBruto - Number(this.desconto || 0);
            return total > 0 ? total : 0;
        },

        get troco() {
            if (this.formaPagamento !== 'dinheiro') return 0;
            const valor = Number(this.valorRecebido || 0) - this.totalLiquido;
            return valor > 0 ? valor : 0;
        },
    };
}
</script>
@endsection
