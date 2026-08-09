# Inventory Management System

A PHP-based inventory management system for tracking products, categories, pricing, and stock quantities.

## Features

- Product create, read, update, and delete workflows
- Dynamic category management
- Product search and category filtering
- Dashboard statistics for products, categories, inventory quantity, and low-stock items
- Stock indicators for low, medium, and healthy inventory levels
- Admin login and session handling

## Setup

1. Clone the repository.
2. Copy `config/db.example.php` to `config/db.php`.
3. Update `config/db.php` with your local database credentials.
4. Import or create the required MySQL tables for products and categories.
5. Serve the project with PHP, XAMPP, WAMP, or another local PHP server.

## Default Admin Login

- Username: `admin`
- Password: `admin123`

## Notes

The real `config/db.php` file is ignored by Git so database passwords stay local.
