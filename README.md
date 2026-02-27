A estrutura do banco do projeto irá mudar, aplique para mim e crie/edite as migrations responsáveis, 

Produto:

| Campo       | Tipo    |   |
| ----------- | ------- | - |
| id          | bigint  |   |
| product_id  | fk      |   |
| lote        | string  |   |
| validade    | date    |   |
| preco_custo | decimal |   |
| quantidade  | integer |   |
| created_at  |         |   |
| updated_at  |         |   |

Deixe o campo imagem em produto ainda.

Nova migration: vendas

| Campo           | Tipo                                 |
| --------------- | ------------------------------------ |
| id              | bigint                               |
| user_id         | fk (quem vendeu)                     |
| caixa_id        | fk                                   |
| total_bruto     | decimal                              |
| desconto        | decimal                              |
| total_liquido   | decimal                              |
| forma_pagamento | enum                                 |
| status          | enum (aberta, finalizada, cancelada) |
| created_at      |               

Nova migration: itens_vendidos

| Campo            | Tipo    |
| ---------------- | ------- |
| id               | bigint  |
| sale_id          | fk      |
| product_id       | fk      |
| product_batch_id | fk      |
| quantidade       | integer |
| preco_unitario   | decimal |
| subtotal         | decimal |
| created_at       |         |


nova migration: caixa

| Campo            | Tipo                   |
| ---------------- | ---------------------- |
| id               | bigint                 |
| user_id          | fk                     |
| valor_abertura   | decimal                |
| valor_fechamento | decimal                |
| aberto_em        | datetime               |
| fechado_em       | datetime               |
| status           | enum (aberto, fechado) |

nova migration: pagamentos
| Campo      | Tipo                                  |
| ---------- | ------------------------------------- |
| id         | bigint                                |
| sale_id    | fk                                    |
| metodo     | enum (dinheiro, pix, credito, debito) |
| valor      | decimal                               |
| created_at |                                       |


Atualizações nos models:
Produto
public function batches()
{
    return $this->hasMany(ProductBatch::class);
}

Lote_produto
public function product()
{
    return $this->belongsTo(Product::class);
}


venda
public function items()
{
    return $this->hasMany(SaleItem::class);
}

public function payments()
{
    return $this->hasMany(Payment::class);
}


Preciso que atualize também os campos das views que ja estão sendo utilizados esses campos no banco, será necessário mudar a chamada dos itens salvos no banco para essa nova arquitetura.


Crie uma view blade.php em uma pasta chamada PDV e nela deve ser a estrutura resources/views/pdv/caixa.blade.php 

Crie o layout do caixa baseado nas cores das views que ja possui hoje e layout que temos, deve ser algo que não comprometa o layout moderno que ja possuimos, como deve ser reativo o caixa pensei em usar blade + alpine.js , mas não precisa criar controller/service por hora eu irei implementar após ver essa nova estrutura. Apenas faça o que solicitei e nada mais. A tela do caixa deve conter o que é necessário para hoje em dia, insira 

Formas de Pagamento: Botões ou seleção para dinheiro (com cálculo de troco), cartão de crédito/débito, Pix, ou crediário próprio.
Menu de Ações rápidas (Teclas de Atalho): Funções como F1 (Iniciar Venda), F3 (Consulta de Preço), F4 (Finalizar), F6 (Cancelar Item/Desconto), F8 (Cancelar Venda).

Informações de status: Data, hora, status do caixa (aberto/fechado)

Atualização em tempo real: produtos que estão sendo bipados no momento, soma dos produtos

Área de Entrada de Dados: Campo para leitura de código de barras, busca pelo nome do produto ou digitação do código.

Lista de Produtos/Itens: Área central que exibe os produtos escaneados, com descrição, quantidade, valor unitário e total por item.

Permite aplicar descontos ou alterar quantidades.

Emissão de documentos fiscais

Cupom fiscal (NFC-e ou SAT, dependendo do estado).

Nota fiscal eletrônica integrada.


Controle de estoque

***Ponto importante: Atualização automática da saída de produtos direto com banco de dados.

Relatórios rápidos:
Vendas por período, ticket médio, lucro bruto.
Fechamento de caixa com saldo inicial e final.
