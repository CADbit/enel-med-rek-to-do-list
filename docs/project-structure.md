# Struktura Projektu

```
.
├── app/                    # Backend Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Middleware/
│   │   ├── Models/
│   │   └── Services/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   │   └── api.php
│   └── tests/
│       ├── Feature/
│       └── Unit/
│
├── front/                  # Frontend Vue.js
│   ├── src/
│   │   ├── components/    # Komponenty Vue
│   │   ├── stores/        # Store Pinia
│   │   ├── views/         # Widoki
│   │   ├── router/        # Konfiguracja routingu
│   │   └── assets/        # Zasoby statyczne
│   ├── public/            # Statyczne pliki
│   └── tests/             # Testy Vue.js
│
├── docker/                # Konfiguracja Docker
│   └── nginx/            # Konfiguracja Nginx
│
├── docs/                  # Dokumentacja
│   ├── frontend/         # Dokumentacja frontendu
│   └── backend/          # Dokumentacja backendu
│
├── docker-compose.yml     # Konfiguracja Docker Compose
└── Makefile              # Skrypty automatyzacji
```

## Opis Katalogów

### Backend (app/)

- `app/Http/Controllers/` - Kontrolery aplikacji
- `app/Http/Middleware/` - Middleware
- `app/Models/` - Modele Eloquent
- `app/Services/` - Logika biznesowa
- `database/migrations/` - Migracje bazy danych
- `database/seeders/` - Seederzy bazy danych
- `routes/api.php` - Definicje endpointów API
- `tests/` - Testy jednostkowe i funkcjonalne

### Frontend (front/)

- `src/components/` - Komponenty Vue.js
- `src/stores/` - Store Pinia
- `src/views/` - Widoki aplikacji
- `src/router/` - Konfiguracja routingu
- `src/assets/` - Zasoby statyczne (obrazy, style)
- `public/` - Pliki publiczne
- `tests/` - Testy jednostkowe

### Docker

- `docker/nginx/` - Konfiguracja serwera Nginx
- `docker-compose.yml` - Konfiguracja kontenerów
- `app/Dockerfile` - Konfiguracja kontenera Laravel
- `front/Dockerfile` - Konfiguracja kontenera Vue.js

### Dokumentacja

- `docs/` - Główna dokumentacja
- `docs/frontend/` - Dokumentacja frontendu
- `docs/backend/` - Dokumentacja backendu

## Pliki Konfiguracyjne

- `.env` - Zmienne środowiskowe Laravel
- `front/.env` - Zmienne środowiskowe Vue.js
- `composer.json` - Zależności PHP
- `front/package.json` - Zależności Node.js
- `Makefile` - Skrypty automatyzacji

Więcej szczegółów o poszczególnych częściach aplikacji znajdziesz w odpowiednich sekcjach dokumentacji:
- [Frontend](frontend/README.md)
- [Backend](backend/README.md) 