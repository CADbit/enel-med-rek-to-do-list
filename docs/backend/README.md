# Backend

## Technologie

- Laravel 10
- PHP 8.2
- MySQL 8.0
- Docker
- Nginx

## Struktura

```
app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── tests/
    ├── Feature/
    └── Unit/
```

## Funkcjonalności

- REST API
- Zarządzanie zadaniami
- Walidacja danych
- Obsługa błędów
- Testy jednostkowe i funkcjonalne

## Konfiguracja

### Zmienne Środowiskowe

Utwórz plik `.env` w katalogu `app/`:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=enelmed
DB_USERNAME=enelmed
DB_PASSWORD=enelmed

APP_URL=http://localhost:8000
```

### Instalacja

```bash
# Instalacja zależności
make app-install

# Generowanie klucza aplikacji
docker-compose exec app php artisan key:generate

# Migracje bazy danych
make db-migrate
```

## API Endpoints

### Zadania

```
GET    /api/tasks          - Lista zadań
POST   /api/tasks          - Utworzenie zadania
GET    /api/tasks/{id}     - Szczegóły zadania
PUT    /api/tasks/{id}     - Aktualizacja zadania
DELETE /api/tasks/{id}     - Usunięcie zadania
```

### Przykłady Zapytań

```bash
# Pobranie listy zadań
curl http://localhost:8000/api/tasks

# Utworzenie zadania
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Nowe zadanie","description":"Opis zadania"}'

# Aktualizacja zadania
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"status":"completed"}'
```

## Modele

### Task

```php
class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];
}
```

## Kontrolery

### TaskController

```php
class TaskController extends Controller
{
    public function index()
    {
        return Task::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        return Task::create($validated);
    }
}
```

## Testy

```bash
# Uruchomienie testów
make app-test

# Uruchomienie testów z raportem pokrycia
docker-compose exec app php artisan test --coverage
```

## Migracje

```bash
# Utworzenie migracji
docker-compose exec app php artisan make:migration create_tasks_table

# Uruchomienie migracji
make db-migrate

# Wycofanie migracji
docker-compose exec app php artisan migrate:rollback
```

## Rozwiązywanie Problemów

### Problemy z Bazą Danych

1. Sprawdź połączenie:
   ```bash
   docker-compose exec app php artisan db:monitor
   ```
2. Wyczyść cache konfiguracji:
   ```bash
   docker-compose exec app php artisan config:clear
   ```
3. Sprawdź logi:
   ```bash
   docker-compose logs db
   ```

### Problemy z API

1. Sprawdź logi Laravel:
   ```bash
   docker-compose exec app tail -f storage/logs/laravel.log
   ```
2. Wyczyść cache routingu:
   ```bash
   docker-compose exec app php artisan route:clear
   ```
3. Sprawdź dostępne endpointy:
   ```bash
   docker-compose exec app php artisan route:list
   ```

### Problemy z Testami

1. Wyczyść cache:
   ```bash
   docker-compose exec app php artisan cache:clear
   ```
2. Uruchom testy z flagą debug:
   ```bash
   docker-compose exec app php artisan test --debug
   ```
3. Sprawdź konfigurację PHPUnit w `phpunit.xml` 