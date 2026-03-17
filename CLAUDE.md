# VittaSys  
## Sistema Web de Gestão Inteligente

O **VittaSys** é um sistema web focado no **gerenciamento de estoque, vendas, compras, relatórios e análises**, unindo **modernidade, simplicidade e inteligência de dados**.

Este documento descreve **funcionalidades estratégicas**, **módulos essenciais** e **ideias de evolução**, baseadas nas **principais dores** e nos **melhores sistemas do mercado**.

---

## 🎯 Objetivo do Projeto

Criar um **ERP moderno**, simples de usar, com foco em:

- Redução de perdas por vencimento
- Controle inteligente de estoque
- Apoio à tomada de decisão
- Automação de processos manuais
- Compliance com regras do setor
- Integração total (vendas, compras, estoque e análises)

Tudo isso em **um único sistema web**.

---

## 🧠 Principais Dores

- Falta de visibilidade do estoque real
- Produtos vencendo sem alertas
- Compras feitas “no feeling”
- Falta de relatórios claros
- Sistemas antigos e pouco intuitivos
- Processos manuais para NF, pedidos e análises
- Pouca inteligência de dados

O VittaSys nasce para **resolver essas dores**, não apenas cadastrar produtos.

---

## 📦 Módulo de Estoque Avançado

### Funcionalidades essenciais
- Controle de estoque **por lote**
- Controle de **validade**
- Alertas automáticos de vencimento
- Regra **FEFO (First Expiry First Out)**
- Histórico de movimentações
- Estoque mínimo configurável
- Suporte a múltiplas lojas / locais

### Benefícios
- Redução de perdas
- Mais controle e rastreabilidade
- Estoque sempre atualizado em tempo real

---

## 🧾 Módulo de Compras e Recebimento de Mercadorias

### Funcionalidades
- Cadastro de fornecedores
- Pedido de compra interno
- Importação de **XML de Nota Fiscal**
- Atualização automática do estoque após recebimento
- Histórico de compras
- Comparação de preços por fornecedor
- Custo médio automático

### Inteligência
- Sugestão automática de pedido baseada em:
  - Histórico de vendas
  - Estoque mínimo
  - Giro de produtos
  - Sazonalidade

---

## 💳 Módulo de Vendas e Pagamentos (PDV)

### Funcionalidades
- PDV integrado ao estoque
- Baixa automática de produtos
- Múltiplos meios de pagamento:
  - PIX
  - Cartão
  - Dinheiro
- Controle de devoluções e estornos
- Histórico de vendas detalhado

### Diferencial
- Vendas alimentam diretamente os módulos de:
  - Relatórios
  - Análises
  - Sugestão de compras

---

## 📊 Relatórios e Dashboards Inteligentes

### Relatórios estratégicos
- Produtos mais vendidos
- Produtos menos vendidos
- Giro de estoque
- Margem de lucro por produto
- Curva ABC
- Vendas por período
- Vendas por categoria
- Produtos próximos do vencimento

### Dashboards
- Gráficos interativos
- Indicadores em tempo real
- Filtros por data, categoria e loja
- Exportação para PDF / Excel

---

## 📈 Módulo de Análises e Inteligência de Negócio

### Análises avançadas
- Sugestão de reposição automática
- Análise de sazonalidade
- Comparação de preços de compra
- Análise de performance de produtos
- Identificação de produtos parados
- Simulação de impacto de promoções

### Objetivo
Transformar dados em **decisão**, não apenas em números.

---

## 🧑‍⚕️ Funcionalidades Específicas do Nicho Farmacêutico

- Controle de medicamentos por categoria
- Alertas de validade críticos
- Histórico de vendas por medicamento
- Preparação para integração com:
  - Prescrição eletrônica
  - Controle de medicamentos controlados (SNGPC)
- Rastreamento completo de produtos (lote + validade)

---

## 🔗 Integrações Externas

- Gateway de pagamento
- Fornecedores (catálogo e preços)
- Sistema fiscal / contábil
- API para e-commerce do nicho
- Integração com site institucional da loja

---

## 🧩 Arquitetura Sugerida

### Backend (Laravel)
- API REST modular
- Services:
  - InventoryService
  - SalesService
  - PurchaseService
  - AnalyticsService
- Jobs e filas para:
  - Processamento de NF
  - Cálculos de análises
- Autenticação com níveis de permissão

### Frontend (Angular)
- Dashboard reativo
- Gráficos interativos
- Componentização clara
- Experiência simples e objetiva

---

## 🛣️ Roadmap Sugerido

### Fase 1 — Base sólida
- Estoque com validade
- Vendas
- Dashboard básico

### Fase 2 — Inteligência
- Relatórios avançados
- Sugestão de pedidos
- Análises de vendas

### Fase 3 — Integrações
- Pagamentos
- NF de compra
- Integração com fornecedores

### Fase 4 — Diferencial competitivo
- Multi-loja
- BI avançado
- Integração com e-commerce
- Preparação para compliance completo

---

## 🏁 **VittaSys**

---

*VittaSys - gestão inteligente para quem vive o balcão.*

📌 Filament, relatórios e dashboards:

📊 Relatórios de vendas

📈 Gráficos de KPI

🧾 Listagem de movimentos

📦 Gestão de estoque


## Fase 1:

📌 Só registrar venda

📌 Subtrair estoque

📌 Gerar relatório

## Fase 2:

📌 Integrar com API fiscal

📌 Focus NFe

📌 Tecnospeed

📌 Nuvem Fiscal

📌 Sefaz direto


integração com XML - NOTA FISCAL 

Atualização de estoque

Atualização de dados