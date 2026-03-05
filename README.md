Balíkobot REST API application
==============================

Test task for Balikobot


Requirements
------------
PHP 8.4.11+
php-curl
php-json
Composer


Install
--------
composer install


Run (from repository root)
--------------------------
php -S localhost:8000 public/index.php


Test
----
curl -X POST http://localhost:8000/forecast -H 'Content-Type: application/json' -d '{"city" : "Praha"}'

