# Laravel Inventory Management API

A modular inventory system built with Laravel, featuring:

- Real-time inventory tracking  
- Dynamic pricing (based on quantity and time)  
- Transaction processing with audit logs

---

## Features

### 1. Inventory Management

Method  Endpoint  Description 

 `GET`  `/api/inventory/products`  
  List products with pagination 
 `GET`  `/api/inventory/products?search=mouse`  
  Filter products by name 
 `GET`  `/api/inventory/products/{id}`  
  Get product with inventory details 
 `POST` `/api/inventory/inventory/update`  
  Update product inventory quantity 

---

### 2.  Dynamic Pricing *(Optional)*

 Method  Endpoint  Description 
 `GET`  `/api/inventory/products/{id}/price?quantity=10&datetime=2025-06-25 12:00:00` 
  Get dynamically calculated price for a product 

---

### 3.  Transaction Processing

Method  Endpoint  Description 
`POST`  `/api/inventory/transactions/process` 
 Process a sale transaction and update inventory 
`GET`   `/api/audit-logs` 
 View audit logs for processed transactions 

---

## ⚙️ How to Run the Project

Follow these steps to run it locally:

composer install
php artisan migrate --seed
php artisan serve
