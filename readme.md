# Microservices

# Установка
1. Склонировать репзиторий
2. В корне, в UserManagementService и TaskManagementService скопировать файл .env.example
3. Запустить команду `docker compose up -d --build`
4. Ввести в контейнерах user-php-fpm и task-php-fpm ввести команды
   1. `composer install`
   2. `php artisan key:generate`
   3. `php artisan migrate`
   4. `php artisan db:seed`
5. Также в контейнере task-php-fpm нужно выполнить команду `php artisan rabbitmq:listen`

---
После этого сервисы будут доступны по адресам http://localhost:8000 и http://localhost:8001
Панель управления rabbitmq доступна по адресу http://localhost:15672