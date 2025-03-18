# API de Gestão Bancária

## 1. Resumo

Este sistema fornece uma API para gestão de contas bancárias e transações financeiras. Através dos endpoints `/conta` e `/transacao`, é possível criar contas, consultar saldos e realizar transações como débito, crédito e Pix, com taxas diferenciadas.

## 2. Requisitos Funcionais

### 2.1 Criação de Conta

* **Endpoint:** `POST /conta`
* **Entrada:** JSON com `numero_conta` (inteiro) e `saldo` (float).
* **Saída:** HTTP 201 e JSON com `numero_conta` e `saldo` da conta criada.
* **Validação:**
    * O sistema deve validar se a conta já existe, retornando um erro caso positivo.

### 2.2 Consulta de Saldo

* **Endpoint:** `GET /conta?numero_conta={numero_conta}`
* **Entrada:** `numero_conta` como parâmetro de consulta.
* **Saída:**
    * HTTP 200 e JSON com `numero_conta` e `saldo` se a conta existir.
    * HTTP 404 se a conta não existir.

### 2.3 Transações Financeiras

* **Endpoint:** `POST /transacao`
* **Entrada:** JSON com `forma_pagamento` (P, C ou D), `numero_conta` e `valor` (float).
* **Saída:**
    * HTTP 201 e JSON com `numero_conta` e `saldo` atualizado após a transação.
    * HTTP 404 caso não haja saldo disponível.
* **Formas de pagamento e taxas:**
    * Débito (D): Taxa de 3% sobre o valor da transação.
    * Crédito (C): Taxa de 5% sobre o valor da transação.
    * Pix (P): Sem taxas.
* **Validações:**
    * O sistema deve garantir que as transações não resultem em saldo negativo.

## 3. Formatos de Entrada/Saída

* Todas as entradas e saídas seguem o formato JSON.
* Siglas para as formas de pagamento:
    * P => Pix
    * C => Cartão de Crédito
    * D => Cartão de Débito

## 4. Observações

* As contas não possuem limite de cheque especial (saldo negativo).
* As taxas devem ser acrescidas no valor total descontado da conta bancária.

## 5. Requisitos não Funcionais

* Testes unitários, de integração e end-to-end (E2E).
* Commits bem descritos seguindo boas práticas.
* Código limpo e organizado.
* Persistência dos dados.
* Documentação básica.

