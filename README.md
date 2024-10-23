# SanaSana
Patient registration application with Laravel.

## Deploy
Standing on the project root directory, run:
```
cp .env.example .env
cp src/.env.example src/.env
docker compose build
docker compose up
docker compose exec app composer install && php artisan migrate
```
