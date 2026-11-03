# food-delivery
food delivery application using Flutter and PHP. 

A portfolio food-delivery backend built with PHP 8.1+, MySQL, PDO and JSON REST endpoints.
It models restaurants, menus, customer orders, order items and courier assignment.

## Features
- Customer, restaurant-manager, courier and admin roles
- Restaurant and menu browsing
- Cart-style order creation with server-side price lookup
- Order lifecycle: pending → accepted → preparing → ready → picked_up → delivered
- Admin assignment of a courier
- Order history and role-scoped views
- Prepared statements, password hashing, CSRF/session protection for web pages

## Setup
1. Create a MySQL database and import `database/swiftbite.sql`.
2. Copy `config/config.example.php` to `config/config.php` and set credentials.
3. Point Apache document root at `public/` or place the project under XAMPP `htdocs`.
4. Create an admin with `scripts/create_admin.php` from CLI only.
5. Register test customer, restaurant manager and courier accounts.
6. Use the endpoint examples in `docs/API.md`.

This is a demonstrator, not a production delivery/payment system. Add rate limits, verified accounts,
stock/menu availability checks, payment-provider integration, GPS tracking and automated tests before deployment.
