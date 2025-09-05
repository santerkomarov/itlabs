# Проект - REST API на **Symfony 7** с **API Platform** и админкой на **EasyAdmin**.  
Документация API доступна в Swagger UI на `/api`. Админ-панель — на `/admin`.

## Технологии

- PHP 8.3, Symfony 7.3  
- API Platform 4.1 (OpenAPI 3, Swagger UI)  
- Doctrine ORM + Migrations  
- EasyAdmin 4  

## Локальный запуск
### 1) установить зависимости
composer install

### 2) скопировать пример окружения
Copy-Item .env.example .env.local

### 3) настроить подключение к БД в .env.local
DATABASE_URL="mysql://user:pass@127.0.0.1:3306/itlabs?serverVersion=10.6&charset=utf8mb4"

### 4) миграции
php bin/console doctrine:migrations:migrate --no-interaction

### 5) запустить сервер
Вариант 1: 
Symfony CLI - 
symfony server:start -d

Вариант 2: 
встроенный сервер PHP из каталога public - 
php -S 127.0.0.1:8000 -t public
