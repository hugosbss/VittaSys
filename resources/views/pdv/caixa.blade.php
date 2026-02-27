@extends('layouts.app', ['title' => 'Caixa | VittaSys', 'hideNavbar' => true])

@section('content')
<div class="mx-auto max-w-[1500px] p-4 lg:p-6" x-data="pdvCaixa()" x-init="init()">
    <header class="mb-4 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-soft">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-brand/15 font-extrabold text-brand">PDV</div>
                <div>
                    <h1 class="text-xl font-extrabold text-ink">Frente de Caixa</h1>
                    <p class="text-xs text-slate-500">Operador: {{ auth()->user()->name ?? 'Usuário' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:grid-cols-5">
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
                <div class="rounded-lg bg-slate-50 px-3 py-2 text-xs">
                    <p class="text-slate-500">Saldo caixa</p>
                    <p class="font-bold">R$ <span x-text="dinheiro(resumo.saldo_atual)"></span></p>
                </div>
                <div class="rounded-lg bg-ink px-3 py-2 text-xs text-white">
                    <p class="text-slate-300">Total da venda</p>
                    <p class="text-lg font-extrabold">R$ <span x-text="dinheiro(totalLiquido)"></span></p>
                </div>
            </div>
        </div>
    </header>

    <template x-if="mensagem">
        <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700" x-text="mensagem"></div>
    </template>
    <template x-if="erro">
        <div class="mb-3 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700" x-text="erro"></div>
    </template>

    <div class="grid gap-4 xl:grid-cols-[1.8fr_1fr]">
        <section class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-soft">
                <div class="grid gap-3 md:grid-cols-[1.2fr_1fr_1fr_auto]">
                    <input type="text" x-model="codigoBarras" @keydown.enter.prevent="buscarProdutos()" placeholder="Código de barras / SKU" class="rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" x-model="buscaNome" @keydown.enter.prevent="buscarProdutos()" placeholder="Buscar por nome" class="rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" x-model="codigoInterno" @keydown.enter.prevent="buscarProdutos()" placeholder="Código interno" class="rounded-xl border border-slate-300 px-3 py-2 text-sm" />
                    <button type="button" @click="buscarProdutos()" class="rounded-xl bg-brand px-4 py-2 text-sm font-bold text-white">Buscar</button>
                </div>

                <div class="mt-3 rounded-xl border border-slate-200" x-show="resultadosBusca.length > 0">
                    <div class="max-h-64 overflow-y-auto">
                        <template x-for="produto in resultadosBusca" :key="produto.id">
                            <button type="button" @click="adicionarProduto(produto)" class="flex w-full items-center justify-between border-b border-slate-100 px-3 py-2 text-left text-sm hover:bg-slate-50">
                                <span>
                                    <strong x-text="produto.nome"></strong>
                                    <span class="text-xs text-slate-500" x-show="produto.sku"> - SKU: <span x-text="produto.sku"></span></span>
                                </span>
                                <span class="text-xs text-slate-600">Estoque: <span x-text="produto.estoque_atual"></span> | R$ <span x-text="dinheiro(produto.preco_custo_atual)"></span></span>
                            </button>
                        </template>
                    </div>
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

                <div class="mt-3 space-y-3" x-show="formaPagamento === 'pix'">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button type="button" @click="pixModo = 'qrcode'" :class="pixModo === 'qrcode' ? 'border-brand bg-teal-50 text-teal-800 font-semibold' : 'border-slate-300 bg-white text-slate-700'" class="rounded-lg border px-3 py-2">PIX QR Code</button>
                        <button type="button" @click="pixModo = 'copia_cola'" :class="pixModo === 'copia_cola' ? 'border-brand bg-teal-50 text-teal-800 font-semibold' : 'border-slate-300 bg-white text-slate-700'" class="rounded-lg border px-3 py-2">PIX Copia e Cola</button>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-center" x-show="pixModo === 'qrcode'">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Escaneie para pagar</p>
                        <img :src="pixQrCodeUrl" alt="QR Code PIX" class="mx-auto h-44 w-44 rounded-lg border border-slate-200 bg-white p-2" />
                        <p class="mt-2 text-[11px] text-slate-500">Valor: R$ <span x-text="dinheiro(totalLiquido)"></span></p>
                    </div>

                    <div x-show="pixModo === 'copia_cola'">
                        <label class="mb-1 block text-xs font-semibold text-slate-500">Código PIX (copia e cola)</label>
                        <textarea x-model="pixPayload" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs"></textarea>
                        <button type="button" @click="copiarPixPayload()" class="mt-2 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700">Copiar código</button>
                    </div>
                </div>

                <div class="mt-3 space-y-3" x-show="formaPagamento === 'credito' || formaPagamento === 'debito'">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-500">Número do cartão</label>
                        <input type="text" x-model="cartao.numero" placeholder="0000 0000 0000 0000" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-500">Nome impresso</label>
                        <input type="text" x-model="cartao.nomeTitular" placeholder="Nome do titular" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-500">Validade</label>
                            <input type="text" x-model="cartao.validade" placeholder="MM/AA" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-500">CVV</label>
                            <input type="password" x-model="cartao.cvv" placeholder="***" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-500">Bandeira</label>
                            <select x-model="cartao.bandeira" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="">Selecione</option>
                                <option value="visa">Visa</option>
                                <option value="mastercard">Mastercard</option>
                                <option value="elo">Elo</option>
                                <option value="amex">Amex</option>
                            </select>
                        </div>
                        <div x-show="formaPagamento === 'credito'">
                            <label class="mb-1 block text-xs font-semibold text-slate-500">Parcelas</label>
                            <select x-model.number="cartao.parcelas" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <template x-for="n in 12" :key="n">
                                    <option :value="n" x-text="`${n}x`"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="button" :disabled="processando" @click="finalizarVenda()" class="mt-4 w-full rounded-xl bg-emerald-700 px-4 py-3 text-sm font-extrabold text-white disabled:cursor-not-allowed disabled:opacity-60">
                    <span x-show="!processando">F4 Finalizar Venda</span>
                    <span x-show="processando">Processando...</span>
                </button>
            </section>
        </aside>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function pdvCaixa() {
    return {
        caixaId: @json($caixaId ?? null),
        resumo: @json($resumoCaixa ?? []),
        statusData: '',
        statusHora: '',
        caixaAberto: true,
        codigoBarras: '',
        buscaNome: '',
        codigoInterno: '',
        resultadosBusca: [],
        desconto: 0,
        valorRecebido: 0,
        formaPagamento: 'dinheiro',
        pixModo: 'qrcode',
        pixPayload: '',
        cartao: {
            numero: '',
            nomeTitular: '',
            validade: '',
            cvv: '',
            bandeira: '',
            parcelas: 1,
        },
        itens: [],
        processando: false,
        mensagem: '',
        erro: '',
        rotas: {
            buscarProdutos: @json(route('caixa.buscar-produtos')),
            finalizar: @json(route('caixa.finalizar')),
        },
        csrfToken: @json(csrf_token()),

        init() {
            this.atualizarHorario();
            this.gerarPixPayload();
            setInterval(() => this.atualizarHorario(), 1000);
        },

        atualizarHorario() {
            const agora = new Date();
            this.statusData = agora.toLocaleDateString('pt-BR');
            this.statusHora = agora.toLocaleTimeString('pt-BR');
        },

        async buscarProdutos() {
            this.erro = '';
            this.mensagem = '';

            const termo = (this.codigoBarras || this.codigoInterno || this.buscaNome || '').trim();
            if (!termo) {
                this.erro = 'Informe um código ou nome para buscar produtos.';
                return;
            }

            try {
                const resposta = await fetch(`${this.rotas.buscarProdutos}?termo=${encodeURIComponent(termo)}`, {
                    headers: { 'Accept': 'application/json' },
                });

                const data = await resposta.json();
                if (!resposta.ok) {
                    this.erro = data.message || 'Erro ao buscar produtos.';
                    return;
                }

                this.resultadosBusca = Array.isArray(data.produtos) ? data.produtos : [];

                if (this.resultadosBusca.length === 0) {
                    this.erro = 'Nenhum produto encontrado.';
                    return;
                }

                if (this.resultadosBusca.length === 1) {
                    this.adicionarProduto(this.resultadosBusca[0]);
                }
            } catch (e) {
                this.erro = 'Falha de comunicação ao buscar produtos.';
            }
        },

        adicionarProduto(produto) {
            this.erro = '';
            this.mensagem = '';

            const preco = Number(produto.preco_custo_atual || 0);
            if (preco <= 0) {
                this.erro = 'Produto sem preço válido.';
                return;
            }

            const existente = this.itens.find((item) => item.produto_id === produto.id);
            if (existente) {
                existente.quantidade += 1;
                this.recalcularItem(existente);
            } else {
                this.itens.push({
                    produto_id: produto.id,
                    descricao: produto.nome,
                    lote: produto.lote_atual || '-',
                    quantidade: 1,
                    preco_unitario: preco,
                    subtotal: preco,
                });
            }

            this.resultadosBusca = [];
            this.codigoBarras = '';
            this.buscaNome = '';
            this.codigoInterno = '';
        },

        recalcularItem(item) {
            item.quantidade = Math.max(1, Number(item.quantidade || 1));
            item.subtotal = item.quantidade * Number(item.preco_unitario || 0);
        },

        removerItem(index) {
            this.itens.splice(index, 1);
        },

        async finalizarVenda() {
            this.erro = '';
            this.mensagem = '';

            if (this.itens.length === 0) {
                this.erro = 'Adicione ao menos um item antes de finalizar.';
                return;
            }

            if (this.formaPagamento === 'dinheiro' && Number(this.valorRecebido || 0) < this.totalLiquido) {
                this.erro = 'Valor recebido é menor que o total da venda.';
                return;
            }

            const erroPagamento = this.validarDadosPagamento();
            if (erroPagamento) {
                this.erro = erroPagamento;
                return;
            }

            const pagamentos = this.montarPagamentos();
            const payload = {
                caixa_id: this.caixaId,
                desconto: Number(this.desconto || 0),
                forma_pagamento: this.formaPagamento,
                itens: this.itens.map((item) => ({
                    produto_id: item.produto_id,
                    quantidade: Number(item.quantidade || 1),
                    preco_unitario: Number(item.preco_unitario || 0),
                })),
                pagamentos,
                dados_pagamento: this.obterDadosPagamento(),
            };

            this.processando = true;

            try {
                const resposta = await fetch(this.rotas.finalizar, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify(payload),
                });

                const data = await resposta.json();

                if (!resposta.ok) {
                    if (data.errors) {
                        const primeiraChave = Object.keys(data.errors)[0];
                        this.erro = data.errors[primeiraChave][0] || 'Erro ao finalizar venda.';
                    } else {
                        this.erro = data.message || 'Erro ao finalizar venda.';
                    }
                    return;
                }

                this.resumo = data.resumo_caixa || this.resumo;
                this.itens = [];
                this.desconto = 0;
                this.valorRecebido = 0;
                this.pixModo = 'qrcode';
                this.limparDadosCartao();
                this.gerarPixPayload();
                this.resultadosBusca = [];
                this.mensagem = data.message || 'Venda finalizada com sucesso.';
            } catch (e) {
                this.erro = 'Falha de comunicação ao finalizar venda.';
            } finally {
                this.processando = false;
            }
        },

        montarPagamentos() {
            if (this.formaPagamento === 'crediario') {
                return [];
            }

            const valor = Number(this.totalLiquido || 0);
            if (valor <= 0) {
                return [];
            }

            return [
                {
                    metodo: this.formaPagamento,
                    valor,
                }
            ];
        },

        validarDadosPagamento() {
            if (this.formaPagamento === 'pix') {
                if (this.pixModo === 'copia_cola' && this.pixPayload.trim() === '') {
                    return 'Informe o código PIX para pagamento copia e cola.';
                }
                return '';
            }

            if (this.formaPagamento === 'credito' || this.formaPagamento === 'debito') {
                if (this.cartao.numero.replace(/\s/g, '').length < 13) return 'Informe um número de cartão válido.';
                if (this.cartao.nomeTitular.trim() === '') return 'Informe o nome do titular.';
                if (!/^\d{2}\/\d{2}$/.test(this.cartao.validade)) return 'Validade do cartão deve estar em MM/AA.';
                if (!/^\d{3,4}$/.test(this.cartao.cvv)) return 'CVV inválido.';
                if (this.cartao.bandeira === '') return 'Selecione a bandeira do cartão.';
            }

            return '';
        },

        obterDadosPagamento() {
            if (this.formaPagamento === 'pix') {
                return {
                    pix: {
                        modo: this.pixModo,
                        payload: this.pixPayload,
                    },
                };
            }

            if (this.formaPagamento === 'credito' || this.formaPagamento === 'debito') {
                return {
                    cartao: {
                        numero_mascarado: this.mascararCartao(this.cartao.numero),
                        nome_titular: this.cartao.nomeTitular,
                        validade: this.cartao.validade,
                        cvv_informado: this.cartao.cvv.length > 0,
                        bandeira: this.cartao.bandeira,
                        parcelas: this.formaPagamento === 'credito' ? Number(this.cartao.parcelas || 1) : 1,
                    },
                };
            }

            return {};
        },

        gerarPixPayload() {
            const id = Math.random().toString(36).slice(2, 10).toUpperCase();
            this.pixPayload = `00020126580014BR.GOV.BCB.PIX0136vittasys+${id}@pix.local520400005303986540${(this.totalLiquido || 0).toFixed(2)}5802BR5920VITTASYS FARMACIA6009SAOPAULO62070503***6304ABCD`;
        },

        copiarPixPayload() {
            if (!this.pixPayload) return;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(this.pixPayload);
                this.mensagem = 'Código PIX copiado.';
                return;
            }

            this.mensagem = 'Seu navegador não suporta cópia automática.';
        },

        limparDadosCartao() {
            this.cartao = {
                numero: '',
                nomeTitular: '',
                validade: '',
                cvv: '',
                bandeira: '',
                parcelas: 1,
            };
        },

        mascararCartao(numero) {
            const apenasNumeros = String(numero || '').replace(/\D/g, '');
            if (apenasNumeros.length < 4) return '****';
            const final = apenasNumeros.slice(-4);
            return `**** **** **** ${final}`;
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

        get pixQrCodeUrl() {
            const payload = encodeURIComponent(this.pixPayload || '');
            return `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${payload}`;
        },
    };
}
</script>
@endsection
