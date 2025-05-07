# Rozwiązywanie Problemów

## Sprawdzanie Statusu

### Sprawdzenie Statusu Usług

```bash
# Sprawdzenie statusu wszystkich usług
make health

# Sprawdzenie logów
make logs
```

### Sprawdzenie Portów

```bash
# Sprawdzenie zajętych portów
docker-compose ps
```

## Problemy z Dockerem

### Kontenery Nie Uruchamiają Się

1. Sprawdź logi:
   ```bash
   docker-compose logs
   ```

2. Sprawdź status kontenerów:
   ```bash
   docker-compose ps
   ```

3. Spróbuj zrestartować:
   ```bash
   make restart
   ```

### Problemy z Zasobami

1. Wyczyść nieużywane zasoby:
   ```bash
   make docker-prune
   ```

2. Wyczyść wszystkie kontenery i obrazy:
   ```bash
   make docker-clean
   ```

## Problemy z Frontendem

### Aplikacja Nie Działa

1. Sprawdź logi frontendu:
   ```bash
   docker-compose logs frontend
   ```

2. Zrestartuj kontener:
   ```bash
   docker-compose restart frontend
   ```

3. Sprawdź zmienne środowiskowe:
   ```bash
   docker-compose exec frontend env
   ```

### Problemy z Buildem

1. Wyczyść cache:
   ```bash
   docker-compose exec frontend npm cache clean --force
   ```

2. Usuń node_modules:
   ```bash
   docker-compose exec frontend rm -rf node_modules
   make front-install
   ```

3. Sprawdź wersję Node.js:
   ```bash
   docker-compose exec frontend node --version
   ```

## Problemy z Backendem

### Aplikacja Nie Działa

1. Sprawdź logi Laravel:
   ```bash
   docker-compose exec app tail -f storage/logs/laravel.log
   ```

2. Sprawdź uprawnienia:
   ```bash
   docker-compose exec app chmod -R 777 storage bootstrap/cache
   ```

3. Wyczyść cache:
   ```bash
   make app-cache-clear
   ```

### Problemy z Bazą Danych

1. Sprawdź połączenie:
   ```bash
   docker-compose exec app php artisan db:monitor
   ```

2. Sprawdź migracje:
   ```bash
   docker-compose exec app php artisan migrate:status
   ```

3. Zresetuj bazę danych:
   ```bash
   make db-fresh
   ```

## Problemy z Nginx

### Strona Nie Działa

1. Sprawdź logi Nginx:
   ```bash
   docker-compose logs nginx
   ```

2. Sprawdź konfigurację:
   ```bash
   docker-compose exec nginx nginx -t
   ```

3. Zrestartuj Nginx:
   ```bash
   docker-compose restart nginx
   ```

## Problemy z Testami

### Testy Nie Przechodzą

1. Sprawdź logi testów:
   ```bash
   make test
   ```

2. Uruchom testy z debugowaniem:
   ```bash
   # Frontend
   docker-compose exec frontend npm run test -- --debug

   # Backend
   docker-compose exec app php artisan test --debug
   ```

3. Sprawdź konfigurację testów:
   ```bash
   # Frontend
   cat front/vitest.config.js

   # Backend
   cat app/phpunit.xml
   ```

## Problemy z Instalacją

### Instalacja Nie Działa

1. Sprawdź wymagania:
   ```bash
   docker --version
   docker-compose --version
   ```

2. Wyczyść środowisko:
   ```bash
   make docker-clean
   ```

3. Spróbuj zainstalować ponownie:
   ```bash
   make install
   ```

## Kontakt i Wsparcie

W przypadku problemów, które nie zostały opisane powyżej:

1. Sprawdź dokumentację:
   - [Frontend](frontend/README.md)
   - [Backend](backend/README.md)

2. Sprawdź logi aplikacji:
   ```bash
   make logs
   ```

3. Sprawdź status usług:
   ```bash
   make health
   ``` 