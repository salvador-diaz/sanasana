# SanaSana
Patient registration application with Laravel.

## Deploy
Standing on the project root directory, run:
```
cp .env.example .env
cp src/.env.example src/.env
docker compose build
docker compose up -d
docker compose exec app composer install && php artisan migrate
```

Define your email credentials in `src/.env`:
```
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

## Usage
The API for this development environment is deployed by default on port 8000.
Patient creation example:
```
POST http://localhost:8000/api/patients
```

For direct database acces on development environment, MySQL port is exposed:
```
mysql -u root -h 0.0.0.0 sanasana -p123456789
```
