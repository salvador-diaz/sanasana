# SanaSana
Patient registration application with Laravel.

## Development Deploy
Standing on the project root directory, run:
```
cp .env.example .env
cp src/.env.example src/.env
```
Define your email credentials in `src/.env` (Mailtrap account recommended):
```
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

Init containers. You may need to use sudo for docker usage:
```
docker compose build
docker compose up -d
docker compose exec app php artisan migrate
```

and start listening for queued jobs:
```
docker compose exec app php artisan queue:listen
```

Now everything is set up to start using the API.

## Usage
The API for the development environment is deployed by default on port 8000.
Patient creation example:
```
POST http://localhost:8000/api/patients
```
Note: patient phone number should follow E.164 standard format: `/^\+[1-9]\d{0,14}$/`.

For direct database access on development environment, MySQL port is exposed:
```
mysql -u root -h 0.0.0.0 sanasana -p123456789
```
