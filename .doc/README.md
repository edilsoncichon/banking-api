# Banking Management API

## 1. Abstract

This system provides an API for managing bank accounts and financial transactions. Through the `/account` and `/transaction` endpoints, it is possible to create accounts, check balances, and perform transactions such as debit, credit, and Pix, with differentiated fees.

## 2. Functional Requirements

### 2.1 Account Creation

* **Endpoint:** `POST /account`
* **Input:** JSON with `account_number` (integer) and `balance` (float).
* **Output:** HTTP 201 and JSON with `account_number` and `balance` of the created account.
* **Validation:**
    * The system must validate if the account already exists, returning an error if it does.

### 2.2 Balance Inquiry

* **Endpoint:** `GET /account?account_number={account_number}`
* **Input:** `account_number` as a query parameter.
* **Output:**
    * HTTP 200 and JSON with `account_number` and `balance` if the account exists.
    * HTTP 404 if the account does not exist.

### 2.3 Financial Transactions

* **Endpoint:** `POST /transaction`
* **Input:** JSON with `payment_method` (P, C, or D), `account_number`, and `value` (float).
* **Output:**
    * HTTP 201 and JSON with `account_number` and updated `balance` after the transaction.
    * HTTP 400 if there is not enough available balance.
* **Payment Methods and Fees:**
    * Debit (D): 3% fee on the transaction value.
    * Credit (C): 5% fee on the transaction value.
    * Pix (P): No fees.
* **Validations:**
    * The system must ensure that transactions do not result in a negative balance.

## 3. Input/Output Formats

* All inputs and outputs follow the JSON format.
* Abbreviations for payment methods:
    * P => Pix
    * C => Credit Card
    * D => Debit Card

## 4. Notes

* Accounts do not have an overdraft limit (negative balance).
* The fees must be added to the total value that will be discounted from the bank account.

## 5. Non-Functional Requirements

* Unit, integration, and end-to-end (E2E) tests.
* Well-described commits following best practices.
* Clean and organized code.
* Data persistence.
* Basic documentation.
